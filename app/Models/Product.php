<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'name',
        'price',
        'description',
        'brand',
        'weight',
        'image'
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
        return $this->hasMany(OrderDetail::class, 'product_id');
    }

    // Metode untuk memeriksa ketersediaan stok
    public function isAvailable()
    {
        return $this->stock > 0;
    }
    
} 