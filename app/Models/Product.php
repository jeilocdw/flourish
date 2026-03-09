<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = ['name', 'sku', 'barcode', 'category_id', 'brand_id', 'unit_id', 'cost_price', 'sell_price', 'tax_rate', 'alert_quantity', 'expiry_date', 'is_active'];
    protected $casts = ['is_active' => 'boolean', 'expiry_date' => 'date', 'cost_price' => 'decimal:2', 'sell_price' => 'decimal:2', 'tax_rate' => 'decimal:2'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function productStore(): HasMany
    {
        return $this->hasMany(ProductStore::class);
    }

    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }
}
