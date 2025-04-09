<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'method',
        'status',
        'amount',
        'transaction_id',
        'payment_code',
        'payment_date',
        'receipt_url',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'array',
        'payment_date' => 'datetime'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    public function refunds()
    {
        return $this->hasMany(OrderRefund::class, 'payment_id');
    }
}