<?php

namespace App\Http\Controllers;

use App\Traits\ErrorsTrait;
use App\Traits\ImageTrait;
use App\Traits\NavTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class StoreController extends Controller
{
    use ErrorsTrait, NavTrait, ImageTrait;
    public function getStorePage(Request $request, int $storeId) : Response
    {
        define("PRODUCT_COUNT_ON_PAGE", 5);
        $pageCount = $this->getPageCount(PRODUCT_COUNT_ON_PAGE);
        $pageNum = $this->getPageNum($request, $pageCount);

        $store = DB::table('stores')->find($storeId);
        if (!$store)
            return $this->getErrorPage("Такого магазина не существует!", 404);

        $imagePath = $this->getStoreImagePath($store);

        $products = DB::table('products')
            ->where('store_id', $store->id)
            ->skip(($pageNum - 1) * PRODUCT_COUNT_ON_PAGE)
            ->take($pageNum * PRODUCT_COUNT_ON_PAGE)
            ->get();
        $productIds = [];
        foreach ($products as $product)
            $productIds[] = $product->id;

        $reviews = DB::table('reviews')->whereIn('product_id', $productIds)->get();
        $rating = 0;
        if (count($reviews) > 0) {
            foreach ($reviews as $review) {
                $rating += $review->elevation;
            }
            $rating /= count($reviews);
        }

        $orderCount = DB::table('orders')->whereIn('product_id', $productIds)->count();

        $productsImagesPaths = $this->getProductsImagesPaths($productIds);

        return Response(view('store', [
            'imagePath' => $imagePath,
            'storeName' => $store->name,
            'rating' => $rating,
            'reviewCount' => count($reviews),
            'orderCount' => $orderCount,
            'products' => $products,
            'productsImagesPaths' => $productsImagesPaths,
            'pageNum' => $pageNum,
            'pageCount' => $pageCount
        ]));
    }

    public function modifyStoreAvatar(Request $request) : Response
    {
        $request->validate([
            'inputImage' => 'image',
        ]);

        $userAndErrorPage = $this->getUserAndErrorResponse($request);
        if ($userAndErrorPage[1])
            return $userAndErrorPage[1];
        else
            $user = $userAndErrorPage[0];

        $store = DB::table('stores')->where('seller_id', $user->id)->first();
        if (!$store)
            return response()->json(['message' => 'Магазин не найден!'], 404);

        $image = $request->file('inputImage');
        $imageId = $imageId = $this->saveImageAndGetId($image, $user, 'images/avatars/store/');

        DB::table('stores')->where('id', $store->id)->update(['image_id' => $imageId]);

        return response(status: 200);
    }

    public function modifyStoreName(Request $request)
    {
        $userAndErrorResponse = $this->getUserAndErrorResponse($request);
        if ($userAndErrorResponse[1])
            return $userAndErrorResponse[1];
        else
            $user = $userAndErrorResponse[0];

        $store = DB::table('stores')->where('seller_id', $user->id)->first();
        if (!$store)
            return response()->json(['message' => 'Магазин не найден!'], 404);

        $newName = $request->input('inputName');

        DB::table('stores')->where('id', $store->id)->update(['name' => $newName]);

        return response(status: 200);
    }
}
