<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RatingMedia extends Model
{
    protected $fillable = [
        'rating_id',
        'file_path',
    ];

    public function rating()
    {
        return $this->belongsTo(Rating::class);
    }
}
