<?php

namespace App\Observers;

use Illuminate\Support\Facades\Cache;

class ProductObserver
{
    public function created(): void
    {
        if (Cache::supportsTags()) {
            Cache::tags(['products'])->flush();
        }
    }

    public function updated(): void
    {
        if (Cache::supportsTags()) {
            Cache::tags(['products'])->flush();
        }
    }

    public function deleted(): void
    {
        if (Cache::supportsTags()) {
            Cache::tags(['products'])->flush();
        }
    }
}
