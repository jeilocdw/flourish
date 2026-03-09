<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductStore extends Model
{
    protected $table = 'product_store';
    public $timestamps = true;
    protected $fillable = ['product_id', 'store_id', 'quantity', 'alert_quantity'];
    protected $casts = ['quantity' => 'integer', 'alert_quantity' => 'integer'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
}
