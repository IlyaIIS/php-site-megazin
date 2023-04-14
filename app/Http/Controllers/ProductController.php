<?php

namespace App\Http\Controllers;

use App\Traits\ErrorsTrait;
use App\Traits\ImageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    use ErrorsTrait, ImageTrait;
    public function getProductPage(Request $request, int $product_id) : Response
    {
        $product = DB::table('products')->find($product_id);

        if (!$product)
            return $this->getErrorPage('Этого продукта не существует!', 404);

        $reviews = DB::table('reviews')->where('product_id', $product_id)->get();

        $rating = 0;
        if (count($reviews) > 0) {
            foreach ($reviews as $review) {
                $rating += $review->elevation;
            }
            $rating /= count($reviews);
        }

        $storeName = DB::table('stores')->find($product->store_id)->name;

        $orderCount = count(DB::table('orders')->where('product_id', $product_id)->get() ?: array());

        $firstImageId = DB::table('product_images')->where('product_id', $product_id)->first()->image_id;
        $firstImage = DB::table('images')->find($firstImageId);
        $imagePath = $firstImage->path . $firstImage->name;

        $properties = DB::table('product_properties')->where('product_id', $product_id)->get();
        $propertyNames = [];
        foreach ($properties as $property) {
            $propertyName = DB::table('properties')->find($property->property_id)->name;
            $propertyNames[] = $propertyName;
        }

        $reviewsImagesIds = [];
        $reviewsUsersIds = [];
        foreach ($reviews as $review) {
            $reviewsImagesIds[] = $review->image_id;
            $reviewsUsersIds[] = $review->author_id;
        }
        $reviewsImagesPaths = DB::table('images')->whereIn('id', $reviewsImagesIds)->get();
        $reviewsUsers = DB::table('users')->whereIn('id', $reviewsUsersIds)->get();

        $reviewsUsersImagesIds = [];
        foreach ($reviewsUsers as $reviewUser) {
            $reviewsUsersImagesIds[] = $reviewUser->image_id;
        }

        $reviewsUsersImagesPaths = [];
        for ($i = 0; $i < count($reviewsUsers); $i++) {
            $image = DB::table('images')->find($reviewsUsersImagesIds[$i]);
            if ($image){
                $reviewsUsersImagesPaths[] = $image->path . $image->name;
            } else {
                $reviewsUsersImagesPaths[] = 'images/user-avatar-placeholder.png';
            }

        }

        return Response(view('product', [
            'product' => $product,
            'rating' => $rating,
            'reviews' => $reviews,
            'reviewsImagesPaths' => $reviewsImagesPaths,
            'reviewsUsers' => $reviewsUsers,
            'reviewsUsersImagesPaths' => $reviewsUsersImagesPaths,
            'storeName' => $storeName,
            'storeId' => $product->store_id,
            'orderCount' => $orderCount,
            'imagePath' => $imagePath,
            'properties' => $properties,
            'propertyNames' => $propertyNames
        ]));
    }

    public function addComment(Request $request): Response
    {
        $request->validate([
            'inputElevation' => 'integer|min:1|max:5',
            'inputImage' => 'image'
        ]);

        $userAndErrorResponse = $this->getUserAndErrorResponse($request);
        if ($userAndErrorResponse[1])
            return $userAndErrorResponse[1];
        else
            $user = $userAndErrorResponse[0];

        $productId = $request->input('productId');

        $image = $request->file('inputImage');
        if ($image)
            $imageId = $this->saveImageAndGetId($image, $user, 'images/reviews/');
        else
            $imageId = null;

        DB::table('reviews')->insert([
            'product_id' => $productId,
            'author_id' => $user->id,
            'content' => $request->input('inputContent'),
            'elevation' => $request->input('inputElevation'),
            'image_id' => $imageId,
        ]);

        return response(status: 200);
    }

    public function addProduct(Request $request): Response
    {
        $request->validate([
            'inputImage' => 'image',
        ]);

        $userAndErrorResponse = $this->getUserAndErrorResponse($request);
        if ($userAndErrorResponse[1])
            return $userAndErrorResponse[1];
        else
            $user = $userAndErrorResponse[0];

        $store = DB::table('stores')->where('seller_id', $user->id)->first();
        if (!$store) {
            return response()->json(['message' => 'У вас нет магазина!'], 404);
        }

        $description = $request->input('inputDescription');
        $name = $request->input('inputName');
        $price = $request->input('inputPrice');
        $count = $request->input('inputCount');
        $categoryId = $request->input('inputCategoryId');

        $productId = DB::table('products')->insertGetId([
            'name' => $name,
            'description' => $description,
            'count' => $count,
            'price' => $price,
            'category_id' => $categoryId,
            'store_id' => $store->id,
        ]);


        $image = $request->file('inputImage');

        $imageId = $this->saveImageAndGetId($image, $user, 'images/products/');

        DB::table('product_images')->insert([
            'product_id' => $productId,
            'image_id' => $imageId
        ]);


        $properties = [];
        foreach ($request->all() as $key => $value)
            if (str_contains($key, "inputProperty"))
                $properties[] = ['id' => explode('_', $key)[1], 'value' => $value];

        $updateData = [];
        foreach ($properties as $property) {
            $updateData[] = [
                'product_id' => $productId,
                'property_id' => $property['id'],
                'value' => $property['value']
            ];
        }
        DB::table('product_properties')->insert($updateData);

        return response(status: 200);
    }
}
