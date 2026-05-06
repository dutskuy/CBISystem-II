<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'brand_id', 'category_id', 'name', 'slug', 'sku',
        'part_number', 'description', 'specification',
        'image', 'price', 'cost_price', 'is_active'
    ];

    // Hitung keuntungan kotor per unit
    public function getProfitAttribute(): float
    {
        return $this->price - $this->cost_price;
    }

    // Hitung margin dalam persen
    public function getMarginPercentAttribute(): float
    {
        if ($this->price <= 0) return 0;
        return round(($this->profit / $this->price) * 100, 2);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function stock()
    {
        return $this->hasOne(ProductStock::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
}