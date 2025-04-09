<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\OrderDetail;
use App\Models\OrderRefund;

class OrderRefundDetail extends Model
{
    protected $fillable = [
        'refund_id',
        'order_item_id',
        'quantity',
        'refund_amount',
        'reason',
        'condition',
        'images'
    ];

    protected $casts = [
        'images' => 'array'
    ];

    public function refund()
    {
        return $this->belongsTo(OrderRefund::class, 'refund_id');
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderDetail::class, 'order_item_id');
    }

    public function getConditionTextAttribute()
    {
        $conditions = [
            'new' => 'Masih Baru',
            'opened' => 'Sudah Dibuka',
            'damaged' => 'Rusak',
            'defective' => 'Cacat Produksi'
        ];
        
        return $conditions[$this->condition] ?? $this->condition;
    }
}