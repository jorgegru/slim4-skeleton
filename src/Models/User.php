<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class User extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'name',
        'password',
        'remember_token',
        'password_reset_token',
        'verification_token',
        'is_active',
        'is_verified',
        'last_login_at',
        'last_login_ip',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'password_reset_token',
        'verification_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'is_verified' => 'boolean',
        'last_login_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the user's last login timestamp in a human-readable format.
     *
     * @return string|null
     */
    public function getLastLoginAtFormattedAttribute(): ?string
    {
        return $this->last_login_at 
            ? $this->last_login_at->diffForHumans()
            : null;
    }

    /**
     * Scope a query to only include active users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include verified users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    /**
     * Update the user's last login timestamp and IP.
     *
     * @param  string|null  $ip
     * @return bool
     */
    public function updateLastLogin(?string $ip = null): bool
    {
        return $this->update([
            'last_login_at' => now(),
            'last_login_ip' => $ip,
        ]);
    }

    /**
     * Check if the user is an administrator.
     * This is a placeholder method that can be customized based on your application's needs.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        // Example: Check if the user has an admin role
        // You can implement your own logic here
        return $this->hasRole('admin');
    }

    /**
     * Check if the user has a specific role.
     * This is a placeholder method that can be customized based on your application's needs.
     *
     * @param  string  $role
     * @return bool
     */
    public function hasRole(string $role): bool
    {
        // Example implementation with a roles relationship
        // You can implement your own logic here
        return $this->roles()->where('name', $role)->exists();
    }

    /**
     * Get the roles that belong to the user.
     * This is a placeholder relationship that can be customized based on your application's needs.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        // Example implementation of a many-to-many relationship with roles
        // You can implement your own relationship here
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
    }
}
