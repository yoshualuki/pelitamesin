<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $table = 'order_details';

    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'product_image',
        'price',
        'buy_price',
        'quantity',
        'weight',
        'variant',
        'notes'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function products()
    {
        return $this->belongsTo(Product::class);
    }

    public function refunds()
    {
        return $this->hasMany(OrderRefundItem::class, 'order_item_id');
    }

    public function getSubtotalAttribute()
    {
        return $this->price * $this->quantity;
    }

    public function getRefundableQuantityAttribute()
    {
        $refunded = $this->refunds()->where('status', '!=', 'rejected')->sum('quantity');
        return $this->quantity - $refunded;
    }
    
} 