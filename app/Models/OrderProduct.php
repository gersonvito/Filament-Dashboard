<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class OrderProduct extends Pivot
{
    // aqui explica sobre el nombre que deberia tener order product (video 6  22:14) al realizar la migracion, sabemos que las tablas tiene que ser en plural...

    protected $table = "order_products";

    protected $fillable = [
        "order_id",
        "product_id",
        "quantity",
        "subTotal"
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
