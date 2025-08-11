<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductBatch extends Model
{
    protected $fillable = [
        'product_id',
        'batch_code',
        'initial_stock',
        'current_stock',
        'expiration_date',
        'manufacture_date',
        'cost_price',
        'is_active',
        'notes'
    ];

    protected $casts = [
        'expiration_date' => 'date',
        'manufacture_date' => 'date',
        'cost_price' => 'decimal:2',
        'is_active' => 'boolean',
        'initial_stock' => 'integer',
        'current_stock' => 'integer'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
