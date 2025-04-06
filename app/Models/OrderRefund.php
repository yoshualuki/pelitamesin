<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderRefund extends Model
{
    protected $fillable = [
        'refund_id',
        'order_id',
        'user_id',
        'amount',
        'admin_id',
        'status',
        'reason',
        'admin_notes',
        'refund_method',
        'bank_account',
        'bank_name',
        'account_name',
        'processed_at',
        'receipt_image'
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_PROCESSED = 'processed';
    const STATUS_REJECTED = 'rejected';

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function items()
    {
        return $this->hasMany(OrderRefundItem::class, 'refund_id');
    }

    // Helper methods
    public function isProcessable()
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function markAsProcessed($receiptImage = null)
    {
        $this->update([
            'status' => self::STATUS_PROCESSED,
            'processed_at' => now(),
            'receipt_image' => $receiptImage
        ]);
    }
}