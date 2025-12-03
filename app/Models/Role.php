<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_system',
        'color',
    ];

    protected $casts = [
        'is_system' => 'boolean',
    ];

    /**
     * Get all permissions for this role
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_permission');
    }

    /**
     * Get all users with this role
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Check if role has a specific permission
     */
    public function hasPermission(string $permissionSlug): bool
    {
        return $this->permissions()->where('slug', $permissionSlug)->exists();
    }

    /**
     * Check if role has any of the given permissions
     */
    public function hasAnyPermission(array $permissionSlugs): bool
    {
        return $this->permissions()->whereIn('slug', $permissionSlugs)->exists();
    }

    /**
     * Check if role has all of the given permissions
     */
    public function hasAllPermissions(array $permissionSlugs): bool
    {
        $count = $this->permissions()->whereIn('slug', $permissionSlugs)->count();
        return $count === count($permissionSlugs);
    }

    /**
     * Assign permissions to role
     */
    public function assignPermissions(array $permissionIds): void
    {
        $this->permissions()->sync($permissionIds);
    }
}
