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
        'website'
    ];

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
}
