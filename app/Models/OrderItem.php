<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function shade()
    {
        return $this->belongsTo(Shade::class);
    }
    protected $guarded = []; // This allows all fields to be filled
}
