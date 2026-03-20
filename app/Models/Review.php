<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function shade()
    {
        return $this->belongsTo(Shade::class);
    }

    protected $guarded = []; // This allows all fields to be filled
}
