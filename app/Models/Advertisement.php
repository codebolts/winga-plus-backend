<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    protected $fillable = ['product_id', 'title', 'banner_image', 'start_date', 'end_date', 'status'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
