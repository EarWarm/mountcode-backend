<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class ProductService
{
    public static function makeProductsList(int $categoryId = null, int $page = 1)
    {
        if (Cache::supportsTags()) {
            return Cache::tags(['products'])->remember(
                'products-' . $categoryId . '-' . $page,
                64500,
                fn() => self::getProducts($categoryId, $page)
            );
        }

        return self::getProducts($categoryId, $page);
    }

    private static function getProducts(int $categoryId = null, int $page = 1): LengthAwarePaginator
    {
        $query = Product::query();

        if ($categoryId != null) {
            $query = $query->where('category_id', $categoryId);
        }

        $query = $query->where('active', true)
            ->WhereNull('archived_at')
            ->where('archived_at', '>', now(), 'or');

        return $query->paginate(perPage: 25, page: $page);
    }
}
