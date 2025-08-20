<?php
// app/Models/Quote.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_number', 'name', 'email', 'phone', 'service_type',
        'project_details', 'estimated_budget', 'preferred_start_date',
        'location', 'attachments', 'status', 'priority', 'internal_notes',
        'quoted_amount', 'quote_valid_until', 'responded_at', 'assigned_to'
    ];

    protected $casts = [
        'attachments' => 'array',
        'estimated_budget' => 'decimal:2',
        'quoted_amount' => 'decimal:2',
        'preferred_start_date' => 'date',
        'quote_valid_until' => 'date',
        'responded_at' => 'datetime'
    ];

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($quote) {
            if (empty($quote->reference_number)) {
                $quote->reference_number = 'GC-' . strtoupper(uniqid());
            }
        });
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }
}