<?php

namespace App\Http\Controllers;

use App\Traits\ImageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends Controller
{
    use ImageTrait;
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
}
