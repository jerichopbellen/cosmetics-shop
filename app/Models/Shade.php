<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shade extends Model
{
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    protected $guarded = []; // This allows all fields to be filled
}
