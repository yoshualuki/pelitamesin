<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\OrderDetail;

class Order extends Model
{
    protected $fillable = [
        'order_id',
        'user_id',
        'total_amount',
        'shipping_cost',
        'discount_amount',
        'final_amount',
        'province',
        'city',
        'district',
        'shipping_address',
        'recipient_name',
        'recipient_email',
        'recipient_phone',
        'courier',
        'service',
        'estimated_delivery',
        'tracking_number',
        'weight',
        'payment_method',
        'payment_status',
        'payment_code',
        'payment_date',
        'voucher_code',
        'status',
        'notes',
        'completed_at',
        'cancel_reason'
    ];

    protected $primaryKey = 'order_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $dates = [
        'payment_date',
        'completed_at',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        // tambahkan field datetime lainnya jika ada
    ];
    
    // Status constants
    const STATUS_WAITING_PAYMENT = 'waiting_payment';
    const STATUS_WAITING_CONFIRMATION = 'waiting_confirmation';
    const STATUS_PROCESSING = 'processing';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_PARTIALLY_REFUNDED = 'partially_refunded';
    const STATUS_REFUNDED = 'refunded';


    public function items()
    {
        return $this->hasMany(OrderDetail::class, 'id', 'id');
    }
    
    public function customer()
    {
        return $this->belongsTo(User::class);
    }
    
    // Define relationship with products through order items
    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_details')
                   ->withPivot('quantity', 'price')
                   ->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function refunds()
    {
        return $this->hasMany(OrderRefund::class, 'order_id', 'order_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'order_id', 'order_id');
    }

    // Helper methods
    public function canRequestRefund()
    {
        return $this->status === self::STATUS_COMPLETED && 
               $this->completed_at &&
               $this->completed_at->diffInDays(now()) <= 14;
    }

    public function getRefundableItems()
    {
        return $this->items()->whereDoesntHave('refunds', function($query) {
            $query->where('status', '!=', 'rejected');
        })->get();
    }
}