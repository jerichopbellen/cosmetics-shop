<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use HasFactory;
    use Searchable;

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

    public function toSearchableArray(): array
    {
        $this->load(['brand', 'category', 'shades']);

        return [
            'id'            => (int) $this->id,
            'name'          => $this->name,
            'description'   => $this->description,
            'finish'        => $this->finish,
            'brand_name'    => $this->brand?->name,
            'category_name' => $this->category?->name,
            'prices'        => $this->shades->pluck('price')->map(fn($p) => (float)$p)->toArray(),
        ];
    }
}