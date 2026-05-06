<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductStock extends Model
{
    protected $fillable = ['product_id', 'quantity', 'min_stock', 'unit'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function isLowStock(): bool
    {
        return $this->quantity <= $this->min_stock;
    }
}