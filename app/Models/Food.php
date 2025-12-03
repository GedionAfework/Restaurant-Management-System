<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Food extends Model
{
    protected $fillable = [
        'name',
        'type',
        'description',
        'picture',
        'price',
        'category_id',
        'availability_times',
        'tags',
        'calories',
        'protein',
        'carbs',
        'fat',
        'allergens',
        'is_available',
        'is_featured',
        'display_order',
        'preparation_time',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'availability_times' => 'array',
        'tags' => 'array',
        'allergens' => 'array',
        'is_available' => 'boolean',
        'is_featured' => 'boolean',
        'display_order' => 'integer',
        'calories' => 'integer',
        'protein' => 'integer',
        'carbs' => 'integer',
        'fat' => 'integer',
        'preparation_time' => 'integer',
    ];

    /**
     * Get the category that owns the food item
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(MenuCategory::class, 'category_id', 'category_id');
    }

    /**
     * Get all variants for this food item
     */
    public function variants(): HasMany
    {
        return $this->hasMany(MenuVariant::class);
    }

    /**
     * Get all add-ons for this food item
     */
    public function addOns(): HasMany
    {
        return $this->hasMany(MenuAddOn::class);
    }

    /**
     * Get orders for this food item
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'food_id');
    }

    /**
     * Check if food is available for a specific time
     */
    public function isAvailableFor(string $time): bool
    {
        if (!$this->is_available) {
            return false;
        }

        if (empty($this->availability_times)) {
            return true; // Available all day if no restrictions
        }

        return in_array($time, $this->availability_times);
    }

    /**
     * Check if food has a specific tag
     */
    public function hasTag(string $tag): bool
    {
        return in_array($tag, $this->tags ?? []);
    }

    /**
     * Get price with variant modifier
     */
    public function getPriceWithVariant(?MenuVariant $variant = null): float
    {
        if ($variant) {
            return $this->price + $variant->price_modifier;
        }
        return $this->price;
    }
}
