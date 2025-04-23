<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\Rating;

class Product extends Model
{
    protected $fillable = [
        'name',
        'price',
        'description',
        'brand',
        'weight',
        'image',
        'stock'
    ];

    protected $primaryKey = 'id'; // Menetapkan id sebagai primary key
    protected $keyType = 'string';
    public $incrementing = false; // Non-incrementing karena menggunakan UUID

    // Menetapkan UUID saat membuat model
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            $product->id = (string) Str::uuid(); // Mengatur uuid dengan UUID
        });
    }

    public function orderItems()
    {
        return $this->hasMany(OrderDetail::class, 'product_id', 'id');
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class, 'product_id');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'product_id');
    }

    public function recentReviews()
    {
        return $this->ratings()
            ->latest()
            ->take(5); // Limit to 5 most recent reviews
    }

    // Metode untuk memeriksa ketersediaan stok
    public function isAvailable()
    {
        return $this->stock > 0;
    }
}
