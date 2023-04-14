<?php

namespace App\Traits;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

trait ImageTrait {
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

    private function getStoreImagePath(mixed $store): string
    {
        $image = DB::table('images')->find($store->image_id);
        if ($image)
            $imagePath = $image->path . $image->name;
        else
            $imagePath = 'images/store-avatar-placeholder.png';

        return $imagePath;
    }

}
