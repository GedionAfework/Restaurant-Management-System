<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Order extends Model
{
    protected $fillable = [
        'customer_id',
        'food_id',
        'table_id',
        'quantity',
        'price',
        'total_amount',
        'status',
        'order_notes',
        'kitchen_notes',
        'preparing_at',
        'ready_at',
        'completed_at',
        'estimated_completion_at',
        'priority',
    ];

    protected $casts = [
        'preparing_at' => 'datetime',
        'ready_at' => 'datetime',
        'completed_at' => 'datetime',
        'estimated_completion_at' => 'datetime',
        'price' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'priority' => 'integer',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function food()
    {
        return $this->belongsTo(Food::class, 'food_id');
    }

    public function table()
    {
        return $this->belongsTo(Table::class, 'table_id', 'table_id');
    }

    /**
     * Get elapsed time since order was placed
     */
    public function getElapsedTimeAttribute(): int
    {
        return $this->created_at->diffInMinutes(Carbon::now());
    }

    /**
     * Get estimated time remaining
     */
    public function getEstimatedTimeRemainingAttribute(): ?int
    {
        if (!$this->estimated_completion_at) {
            return null;
        }
        $remaining = Carbon::now()->diffInMinutes($this->estimated_completion_at, false);
        return $remaining > 0 ? $remaining : 0;
    }

    /**
     * Check if order is overdue
     */
    public function isOverdue(): bool
    {
        if (!$this->estimated_completion_at) {
            return false;
        }
        return Carbon::now()->isAfter($this->estimated_completion_at) && 
               !in_array($this->status, ['ready', 'completed', 'cancelled']);
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'yellow',
            'preparing' => 'blue',
            'ready' => 'green',
            'completed' => 'gray',
            'cancelled' => 'red',
            default => 'gray',
        };
    }
}