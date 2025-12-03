<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenuVariant extends Model
{
    protected $fillable = [
        'food_id',
        'name',
        'price_modifier',
        'size',
        'is_default',
        'display_order',
    ];

    protected $casts = [
        'price_modifier' => 'decimal:2',
        'is_default' => 'boolean',
        'display_order' => 'integer',
    ];

    /**
     * Get the food item that owns this variant
     */
    public function food(): BelongsTo
    {
        return $this->belongsTo(Food::class);
    }

    /**
     * Calculate the actual price for this variant
     */
    public function getPriceAttribute(): float
    {
        return $this->food->price + $this->price_modifier;
    }
}
