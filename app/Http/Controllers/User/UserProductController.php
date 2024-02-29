<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\Product\ProductResourceResource;
use App\Http\Resources\Product\UserProductResource;
use App\Models\UserProduct;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Storage;

class UserProductController extends Controller
{
    public function __invoke(): AnonymousResourceCollection
    {
        return UserProductResource::collection(auth('api')->user()->products);
    }

    public function downloadResource(UserProduct $userProduct, string $uuid)
    {
        if ($userProduct->user_id != auth('api')->user()->id) {
            return response()->json([
                'status' => false,
                'message' => 'У вас нет доступа к данному продукту!'
            ]);
        }

        $resource = $userProduct->product->resources()->where('uuid', $uuid)->first();

        if (!$resource) {
            return response()->json([
                'status' => false,
                'message' => 'Ресурс не найден, попробуйте позже!'
            ]);
        }

        $disk = Storage::disk('resources');
        if (!$disk->exists($resource->uuid . '.jar')) {
            return response()->json([
                'status' => false,
                'message' => 'Ресурс не найден, попробуйте позже!'
            ]);
        }

        return response()->download($disk->path($resource->uuid . '.jar'));
    }

    public function resources(UserProduct $userProduct): AnonymousResourceCollection|JsonResponse
    {
        if ($userProduct->user_id != auth('api')->user()->id) {
            return response()->json([
                'status' => false,
                'message' => 'У вас нет доступа к данному продукту!'
            ]);
        }

        return response()->json([
            'resources' => ProductResourceResource::collection($userProduct->product->resources),
            'archived' => $userProduct->product->isArchived()
        ]);
    }
}
