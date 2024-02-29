<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ProductResource extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $primaryKey = 'uuid';
    protected $fillable = [
        'uuid',
        'name',
        'product_id',
        'version',
        'changelog',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($productResource) {
            $productResource->{$productResource->getKeyName()} = (string)Str::uuid();
        });
    }

    public function getIncrementing(): bool
    {
        return false;
    }

    public function getKeyType(): string
    {
        return 'string';
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
