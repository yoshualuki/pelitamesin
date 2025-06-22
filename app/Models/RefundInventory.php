<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefundInventory extends Model
{
    use SoftDeletes;
    protected $table = 'refund_inventory_db';
    protected $fillable = [
        'product_id',
        'quantity',
        'order_detail_id',
        'order_id',
        'refund_id',
        'created_at',
        'status'
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function orderRefund()
    {
        return $this->belongsTo(OrderRefund::class, 'refund_id', 'refund_id');
    }
}
