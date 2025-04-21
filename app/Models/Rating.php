<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'rating',
        'review',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }
    public function user()
    {
        return $this->order->user();
    }

    public function media()
    {
        return $this->hasMany(RatingMedia::class);
    }
}
