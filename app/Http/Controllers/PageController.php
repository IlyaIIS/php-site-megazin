<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;


class PageController extends Controller
{
    public function getAccountPage(Request $request) : View
    {
        $userAndErrorPage = $this->getUserAndErrorPage($request);
        if ($userAndErrorPage[1])
            return $userAndErrorPage[1];
        else
            $user = $userAndErrorPage[0];

        $avatar = DB::table('images')->find($user->image_id);
        if ($avatar)
            $avatarPath = $avatar->path . $avatar->name;
        else
            $avatarPath = 'images/user-avatar-placeholder.png';

        return view('account_cabinet', [
            'user' => $user,
            'avatarPath' => $avatarPath
        ]);
    }

    private function getErrorPage(string $errorText, int $status): Response
    {
        return Response(view('error', ['errorText' => $errorText]), $status);
    }

    private function getUserAndErrorPage(Request $request): array
    {
        $sessionToken = $request->cookie('SESSION_TOKEN');
        $session = DB::table('sessions')->where('token', $sessionToken)->first();

        if ($session)
            $user = DB::table('users')->find($session->user_id);
        else
            return [null, $this->getErrorPage(
                "Не удалось определить пользователя. Попробуйте авторизоваться заново.",
                404
            )];

        if (!$user)
            return [null, $this->getErrorPage(
                "Не удалось определить пользователя. Попробуйте авторизоваться заново.",
                404
            )];

        return [$user, null];
    }

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

    public function getSellerPage(Request $request) : Response
    {
        $userAndErrorPage = $this->getUserAndErrorPage($request);
        if ($userAndErrorPage[1])
            return $userAndErrorPage[1];
        else
            $user = $userAndErrorPage[0];

        if (!$user->is_seller)
            return $this->getErrorPage("У вас нет прав продавца!", 403);

        $store = DB::table('stores')->where('seller_id', $user->id)->first();
        if (!$store) {
            $storeId = DB::table('stores')->insertGetId([
                'name' => 'Магазин продавца ' . $user->nickname,
                'seller_id' => $user->id
            ]);
            $store = DB::table('stores')->find($storeId);
        }

        $imagePath = $this->getStoreImagePath($store);

        $categories = $this->getCategories();

        return Response(view('seller_cabinet', [
            'store' => $store,
            'imagePath' => $imagePath,
            'categories' => $categories
        ]));
    }

    private function getStoreImagePath(mixed $store): string
    {
        $image = DB::table('images')->find($store->image_id);
        if ($image)
            $imagePath = $image->path . $image->name;
        else
            $imagePath = 'images/store-avatar-placeholder.png';

        return $imagePath;
    }

    private function getCategories() : array
    {
        $categories = DB::table('categories')->get();
        $categoryHierarchy = [];

        //todo: реализовать формирование иерархии
        foreach ($categories as $key => $category) {
            foreach ($categories as $subCategory) {
                if (property_exists($subCategory, 'parent_id') && $subCategory->parent_id == $category->id) {
                    unset($categories[$key]);
                    break;
                }
            }
        }

        foreach ($categories as $category) {
            $categoryHierarchy[$category->id] = ['name' => $category->name];
        }

        return $categoryHierarchy;
    }

    private function saveImageAndGetId(mixed $image, mixed $user, string $inputPath) : int
    {
        $hash = md5_file($image);

        $sameImage = DB::table('images')->where('hash', $hash)->first();
        if ($sameImage) {
            $image = $sameImage;
            $imageId = $image->id;
        } else {
            $path = Storage::put('public/' . $inputPath, $image);
            $path_parts = explode('/', $path);
            $name = end($path_parts);

            $imageId = DB::table('images')->insertGetId([
                'name' => $name,
                'path' => 'storage/' . $inputPath,
                'hash' => $hash,
                'user_id' => $user->id
            ]);
        }

        return $imageId;
    }

    public function getPropertiesOfCategory(Request $request, int $categoryId) : Response
    {
        $propertiesWithId = DB::table('category_properties')->where('category_id', $categoryId)->get();

        $properties = [];
        foreach ($propertiesWithId as $propertyWithId) {
            $properties[] = DB::table('properties')->find($propertyWithId->property_id);
        }

        return response()->json($properties, 200);
    }

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

    private function getProductsImagesPaths(array $productsIds): array
    {
        $productImagesWithIds = DB::table('product_images')->whereIn('product_id', $productsIds)->get();
        $productImagesIds = [];
        foreach ($productImagesWithIds as $imageWithId)
            $productImagesIds[] = $imageWithId->image_id;
        $productsImages = [];
        foreach ($productImagesIds as $productImagesId)
            $productsImages[] = DB::table('images')->find($productImagesId);
        $productsImagesPaths = [];
        foreach ($productsImages as $productsImage)
            $productsImagesPaths[] = $productsImage->path . $productsImage->name;
        return $productsImagesPaths;
    }

    public function getHomePage(Request $request) : Response
    {
        $products = DB::table('products')->take(20)->get();
        $productIds = [];
        foreach ($products as $product)
            $productIds[] = $product->id;
        $productsImagesPaths = $this->getProductsImagesPaths($productIds);

        return Response(view('home', [
            'products' => $products,
            'productsImagesPaths' => $productsImagesPaths
        ]));
    }

    public function getCatalogPage(Request $request) : Response
    {
        define("PRODUCT_COUNT_ON_PAGE", 4);

        $pageCount = $this->getPageCount(PRODUCT_COUNT_ON_PAGE);
        $pageNum = $this->getPageNum($request, $pageCount);

        $products = DB::table('products')
            ->skip(($pageNum - 1) * PRODUCT_COUNT_ON_PAGE)
            ->take(PRODUCT_COUNT_ON_PAGE)
            ->get();
        $productIds = [];
        foreach ($products as $product)
            $productIds[] = $product->id;
        $productsImagesPaths = $this->getProductsImagesPaths($productIds);

        return Response(view('catalog', [
            'products' => $products,
            'productsImagesPaths' => $productsImagesPaths,
            'pageNum' => $pageNum,
            'pageCount' => $pageCount
        ]));
    }

    private function getPageCount(int $productCountOnPage): int
    {
        return ceil(DB::table('products')->count() / $productCountOnPage);
    }

    private function getPageNum(Request $request, int $pageCount): int
    {
        $pageNum = $request->input('page');
        if (!$pageNum || $pageNum < 1)
            $pageNum = 1;
        if ($pageNum > $pageCount)
            $pageNum = $pageCount;

        return $pageNum;
    }

    public function getShoppingCartPage(Request $request) : Response
    {
        $userAndErrorPage = $this->getUserAndErrorPage($request);
        if ($userAndErrorPage[1])
            return $userAndErrorPage[1];
        else
            $user = $userAndErrorPage[0];

        $carts = DB::table('product_carts')->where('user_id', $user->id)->get(['product_id']);
        $productsIds = [];
        foreach ($carts as $cart)
            $productsIds[] = $cart->product_id;

        $products = DB::table('products')->whereIn('id', $productsIds)->get();

        $productsImagesPaths = $this->getProductsImagesPaths($productsIds);

        return Response(view('shopping_cart', [
            'products' => $products,
            'productsImagesPaths' => $productsImagesPaths
        ]));
    }

    public function getOrderMakingPage(Request $request) : Response
    {
        $userAndErrorPage = $this->getUserAndErrorPage($request);
        if ($userAndErrorPage[1])
            return $userAndErrorPage[1];
        else
            $user = $userAndErrorPage[0];

        $productsIds = [];
        foreach ($request->all() as $key => $value)
            if (str_contains($key, "inputCheckbox"))
                $productsIds[] = explode('_', $key)[1];

        $products = DB::table('products')->whereIn('id', $productsIds)->get();
        $productsImagesPaths = $this->getProductsImagesPaths($productsIds);
        $totalPrice = 0;
        foreach ($products as $product)
            $totalPrice += $product->price;

        return Response(view('order_making', [
            'products' => $products,
            'productsImagesPaths' => $productsImagesPaths,
            'totalPrice' => $totalPrice,
            'user' => $user
        ]));
    }

    public function getOrdersPage(Request $request) : Response
    {
        $userAndErrorPage = $this->getUserAndErrorPage($request);
        if ($userAndErrorPage[1])
            return $userAndErrorPage[1];
        else
            $user = $userAndErrorPage[0];

        $orders = DB::table('orders')->where('customer_id', $user->id)->get();
        $productsIds = [];

        foreach ($orders as $order)
            $productsIds[] = $order->product_id;

        $products = DB::table('products')->whereIn('id', $productsIds)->get();
        $productsImagesPaths = $this->getProductsImagesPaths($productsIds);

        $statesIds = [];
        foreach ($orders as $order)
            $statesIds[] = $order->state_id;
        $states = [];
        foreach ($statesIds as $stateId) {
            $states[] = DB::table('order_states')->find($stateId);
        }

        return Response(view('orders', [
            'products' => $products,
            'productsImagesPaths' => $productsImagesPaths,
            'states' => $states,
            'orders' => $orders
        ]));
    }

    public function getAdminPage(Request $request)
    {
        $userAndErrorPage = $this->getUserAndErrorPage($request);
        if ($userAndErrorPage[1])
            return $userAndErrorPage[1];
        else
            $user = $userAndErrorPage[0];

        if (!$user->is_admin)
            return $this->getErrorPage("У вас нет прав администратора!", 403);

        return view('admin_cabinet');
    }
}
