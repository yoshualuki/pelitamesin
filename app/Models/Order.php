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
        'cancel_reason',
        'order_sent_at',
        'waiting_payment_at',
        'order_processed_at',
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
        'completed_at' => 'datetime',
        'waiting_payment_at' => 'datetime',
        'order_processed_at' => 'datetime',
        'order_sent_at' => 'datetime',
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
    const STATUS_WAITING_REFUND = 'waiting_refund';
    const STATUS_WAITING_RETURN = 'waiting_return';
    const STATUS_REFUNDED = 'refunded';


    public function items()
    {
        return $this->hasMany(OrderDetail::class, 'order_id', 'order_id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // Define relationship with products through order items
    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_details', 'product_id', 'product_id')
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function refunds()
    {
        return $this->belongsTo(OrderRefund::class, 'order_id', 'order_id');
    }

    public function cogs()
    {
        $cogs = 0;
        foreach ($this->items as $item) {
            $cogs += $item->price * $item->quantity;
        }
        return $cogs;
    }

    public function hasRating()
    {
        $rating = Rating::where('order_id', $this->order_id)->first();
        return $rating ? true : false;
    }

    // Helper methods
    public function canRequestRefund()
    {
        return $this->status === self::STATUS_SHIPPED &&
            $this->order_sent_at &&
            $this->order_sent_at->diffInDays(now()) <= 7;
    }

    public function getRefundableItems()
    {
        return $this->items()->whereDoesntHave('refunds', function ($query) {
            $query->where('status', '!=', 'rejected');
        })->get();
    }
}
