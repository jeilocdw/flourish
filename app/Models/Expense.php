<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    protected $fillable = ['store_id', 'category', 'amount', 'description', 'date'];
    protected $casts = ['amount' => 'decimal:2', 'date' => 'date'];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
}
