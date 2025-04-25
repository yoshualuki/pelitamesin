<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Rating;


class Product extends Model
{
    protected $fillable = [
        'name',
        'price',
        'description',
        'brand',
        'weight',
        'image',
        'stock',
        'category_id',
        'average_rating',
        'rating_count',
    ];

    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $appends = ['image_url'];
    protected $withCount = ['ratings', 'orderItems'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            $product->id = (string) Str::uuid();
        });
    }

    // Relationships

    public function orderItems()
    {
        return $this->hasMany(OrderDetail::class, 'product_id');
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class, 'product_id');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'product_id');
    }

    // Scopes
    public function scopeTopRated(Builder $query, string $timeRange = 'month')
    {
        return $query->withAvg('ratings', 'rating')
            ->withCount([
                'ratings',
                'ratings as rating_5' => function ($q) {
                    $q->where('rating', 5);
                },
                'ratings as rating_4' => function ($q) {
                    $q->where('rating', 4);
                },
                'ratings as rating_3' => function ($q) {
                    $q->where('rating', 3);
                },
                'ratings as rating_2' => function ($q) {
                    $q->where('rating', 2);
                },
                'ratings as rating_1' => function ($q) {
                    $q->where('rating', 1);
                }
            ]);
    }

    // Accessors
    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : asset('images/default-product.png');
    }

    // Custom methods
    public function recentReviews($limit = 5)
    {
        return $this->ratings()
            ->latest()
            ->take($limit)
            ->get();
    }

    public function salesCount()
    {
        return $this->orderItems()->count();
    }

    public function topReviews($limit = 2)
    {
        return $this->ratings()
            ->orderBy('rating', 'desc')
            ->take($limit)
            ->get();
    }

    public function isAvailable()
    {
        return $this->stock > 0;
    }
}
