<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'product_id',
        'quantity',
        'unit_cost',
        'total_cost',
        'last_restocked_at'
    ];

    protected $casts = [
        'last_restocked_at' => 'datetime',
        'unit_cost' => 'decimal:2',
        'total_cost' => 'decimal:2'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function updateTotalCost()
    {
        $this->total_cost = $this->quantity * $this->unit_cost;
        $this->save();
    }
    
    public function isLowStock()
    {
        return $this->quantity <= $this->minimum_stock;
    }
}