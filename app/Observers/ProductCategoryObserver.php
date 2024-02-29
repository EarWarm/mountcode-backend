<?php

namespace App\Observers;

use Illuminate\Support\Facades\Cache;

class ProductCategoryObserver
{
    public function created(): void
    {
        Cache::forget('product_categories');
        if (Cache::supportsTags()) {
            Cache::tags(['products'])->flush();
        }
    }

    public function updated(): void
    {
        Cache::forget('product_categories');
        if (Cache::supportsTags()) {
            Cache::tags(['products'])->flush();
        }
    }

    public function deleted(): void
    {
        Cache::forget('product_categories');
        if (Cache::supportsTags()) {
            Cache::tags(['products'])->flush();
        }
    }
}
