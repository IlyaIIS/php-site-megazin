<?php

namespace App\Http\Controllers;

use App\Traits\ErrorsTrait;
use App\Traits\ImageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class OrderMakingController extends Controller
{
    use ErrorsTrait, ImageTrait;

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
}
