<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function shades()
    {
        return $this->hasMany(Shade::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function images() 
    {
        return $this->hasMany(ProductImage::class);
    }
}