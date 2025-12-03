<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuCategory extends Model
{
    use HasFactory;

    protected $primaryKey = 'category_id';
    
    protected $fillable = [
        'category_name',
        'description',
        'image',
        'display_order',
        'is_active',
        'icon',
    ];

    protected $casts = [
        'display_order' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get all food items in this category
     */
    public function foodItems(): HasMany
    {
        return $this->hasMany(Food::class, 'category_id');
    }

    /**
     * Get active food items in this category
     */
    public function activeFoodItems(): HasMany
    {
        return $this->hasMany(Food::class, 'category_id')
            ->where('is_available', true)
            ->orderBy('display_order');
    }
}
