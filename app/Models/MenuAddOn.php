<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenuAddOn extends Model
{
    protected $fillable = [
        'food_id',
        'name',
        'price',
        'display_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'display_order' => 'integer',
    ];

    /**
     * Get the food item that owns this add-on
     */
    public function food(): BelongsTo
    {
        return $this->belongsTo(Food::class);
    }
}
