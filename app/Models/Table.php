<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use HasFactory;

    protected $primaryKey = 'table_id';
    
    protected $fillable = [
        'table_number',
        'capacity',
        'location',
        'zone',
        'shape',
        'floor',
        'status',
        'position_x',
        'position_y',
        'notes',
    ];

    protected $casts = [
        'capacity' => 'integer',
        'floor' => 'integer',
        'position_x' => 'integer',
        'position_y' => 'integer',
    ];

    /**
     * Get table assignments for this table
     */
    public function assignments()
    {
        return $this->hasMany(TableAssignment::class, 'table_id');
    }

    /**
     * Get orders for this table
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'table_id');
    }

    /**
     * Get active order for this table
     */
    public function activeOrder()
    {
        return $this->hasOne(Order::class, 'table_id')
            ->whereIn('status', ['pending', 'confirmed', 'preparing']);
    }

    /**
     * Get reservations for this table
     */
    public function reservations()
    {
        return $this->hasManyThrough(
            Reservation::class,
            TableAssignment::class,
            'table_id',
            'reservation_id',
            'table_id',
            'reservation_id'
        );
    }

    /**
     * Check if table is available
     */
    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }

    /**
     * Check if table can accommodate a party size
     */
    public function canAccommodate(int $partySize): bool
    {
        return $this->isAvailable() && $this->capacity >= $partySize;
    }

    /**
     * Get status color for UI
     */
    public function getStatusColor(): string
    {
        return match($this->status) {
            'available' => 'green',
            'occupied' => 'red',
            'reserved' => 'yellow',
            'cleaning' => 'blue',
            default => 'gray',
        };
    }

    /**
     * Get shape icon
     */
    public function getShapeIcon(): string
    {
        return match($this->shape) {
            'round' => '⭕',
            'square' => '⬜',
            'rectangular' => '▭',
            'booth' => '🪑',
            default => '⭕',
        };
    }
}
