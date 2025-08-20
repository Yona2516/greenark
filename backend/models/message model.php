<?php
// app/Models/Message.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'phone', 'subject', 'message', 'type',
        'status', 'priority', 'read_at', 'replied_at', 'admin_reply',
        'assigned_to'
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'replied_at' => 'datetime'
    ];

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function scopeUnread($query)
    {
        return $query->where('status', 'new');
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }
}