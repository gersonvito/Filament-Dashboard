<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{

    protected static function booted()
    {
        static::creating(function ($product) {
            $product->slug = Str::slug($product->name);
        });
    }

    protected $fillable = [
        'name',
        'slug',
        'summary',
        'description',
        'price',
        'code',
        'image',
        'is_active',
        'category_id'
    ];

    // Relación con categoría
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function inventories(): HasMany
    {
        return $this->hasMany(Inventory::class);
    }


}
