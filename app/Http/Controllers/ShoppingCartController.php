<?php

namespace App\Http\Controllers;

use App\Traits\ErrorsTrait;
use App\Traits\ImageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class ShoppingCartController extends Controller
{
    use ErrorsTrait, ImageTrait;
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

    public function addCart(Request $request): Response
    {
        $userAndErrorResponse = $this->getUserAndErrorResponse($request);
        if ($userAndErrorResponse[1])
            return $userAndErrorResponse[1];
        else
            $user = $userAndErrorResponse[0];

        $productId = $request->input('productId');

        $otherCart = DB::table('product_carts')
            ->where(['user_id' => $user->id, 'product_id' => $productId])
            ->first();
        if ($otherCart)
            return response()->json(['message' => 'Товар уже в корзине!'], 409);

        DB::table('product_carts')->insert(['user_id' => $user->id, 'product_id' => $productId]);

        return response(status: 200);
    }

    public function deleteCart(Request $request): Response
    {
        $userAndErrorResponse = $this->getUserAndErrorResponse($request);
        if ($userAndErrorResponse[1])
            return $userAndErrorResponse[1];
        else
            $user = $userAndErrorResponse[0];

        $productId = $request->input('productId');

        DB::table('product_carts')->where('product_id', $productId)->delete();

        return response(status: 200);
    }
}
