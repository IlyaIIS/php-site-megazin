<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait NavTrait {
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
}
