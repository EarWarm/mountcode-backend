<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ProductListRequest;
use App\Http\Resources\Product\ProductResource;
use App\Http\Resources\User\UserResource;
use App\Models\Product;
use App\Models\UserProduct;
use App\Services\ProductService;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    public function __invoke(ProductListRequest $request): AnonymousResourceCollection
    {
        $page = 1;

        if ($request->has('page')) {
            $page = $request->integer('page');
        }

        $category_id = null;
        if ($request->has('category_id')) {
            $category_id = $request->integer('category_id');
        }

        return ProductResource::collection(ProductService::makeProductsList($category_id, $page));
    }

    public function buy(Product $product)
    {
        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Ошибка получения товара, перезагрузите страницу!'
            ]);
        }

        $user = auth('api')->user();

        if ($user->balance < $product->price) {
            return response()->json([
                'status' => false,
                'message' => 'Недостаточно средст!'
            ]);
        }
        if ($user->products()->where('product_id', $product->id)->exists()) {
            return response()->json([
                'status' => false,
                'message' => 'Вы уже приобрели данный товар!'
            ]);
        }

        $user->balance -= $product->price;
        $user->save();

        UserProduct::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'price' => $product->price,
            'bought_at' => Carbon::now()
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Товар приобретён!',
            'user' => new UserResource(auth('api')->user())
        ]);
    }
}
