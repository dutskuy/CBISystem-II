<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'logo', 'origin_country', 'description', 'is_active'
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}