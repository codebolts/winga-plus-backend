<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessProfile extends Model
{
    protected $fillable = [
        'seller_id',
        'business_name',
        'description',
        'logo',
        'website',
        'free_delivery',
        'delivery_cost',
        'delivery_locations',
        'payment_on_delivery',
        'payment_before_delivery',
        'business_address',
        'business_phone'
    ];

    protected $casts = [
        'free_delivery' => 'boolean',
        'payment_on_delivery' => 'boolean',
        'payment_before_delivery' => 'boolean',
        'delivery_locations' => 'array',
    ];

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
}
