<?php

namespace App\Http\Controllers;

use App\Traits\ErrorsTrait;
use App\Traits\ImageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class SellerCabinetController extends Controller
{
    use ErrorsTrait, ImageTrait;

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

    public function getPropertiesOfCategory(Request $request, int $categoryId) : Response
    {
        $propertiesWithId = DB::table('category_properties')->where('category_id', $categoryId)->get();

        $properties = [];
        foreach ($propertiesWithId as $propertyWithId) {
            $properties[] = DB::table('properties')->find($propertyWithId->property_id);
        }

        return response()->json($properties, 200);
    }
}
