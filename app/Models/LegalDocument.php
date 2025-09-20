<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LegalDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'title',
        'content',
        'version',
        'is_active',
        'effective_date'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'effective_date' => 'datetime',
    ];

    // Scope for active documents
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Get document by type
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }
}
