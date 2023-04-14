<?php

namespace App\Http\Controllers;

use App\Traits\ImageTrait;
use App\Traits\NavTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class CatalogController extends Controller
{
    use NavTrait, ImageTrait;
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
}
