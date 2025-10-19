<?php

namespace App\Models;

use App\Models\Traits\HasPhoneVerification;
use App\Models\Traits\HasRoles;
use App\Models\UserDeviceToken;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, HasPhoneVerification, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role_id',
        'phone_verification_code',
        'phone_verification_code_expires_at',
        'phone_verified_at',
        'is_verified',
        'is_active',
        'device_token',
        'uuid',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

/**
 * The attributes that should be cast.
 *
 * @var array<string, string>
 */
protected $casts = [
    'email_verified_at' => 'datetime',
    'phone_verified_at' => 'datetime',
    'password' => 'hashed',
];

/**
 * Get the store that owns the user.
 */
public function store()
{
    return $this->hasOne(Store::class, 'owner_id');
}

    /**
     * Get the role that owns the user.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the stores owned by the user.
     */
    public function stores(): BelongsToMany
    {
        return $this->belongsToMany(Store::class, 'store_users')
            ->withPivot('is_primary')
            ->withTimestamps();
    }

    /**
     * Get the stores where the user is the primary owner.
     */
    public function primaryStores()
    {
        return $this->stores()->wherePivot('is_primary', true);
    }

    /**
     * Get the supplier associated with the user.
     */
    public function supplier()
    {
        return $this->hasOne(Supplier::class);
    }

    /**
     * Check if user is a store owner.
     */
    public function isStoreOwner(): bool
    {
        return $this->role->slug === 'store-owner';
    }

    /**
     * Check if user is a supplier.
     */
    public function isSupplier(): bool
    {
        return $this->role->slug === 'supplier';
    }

    /**
     * Check if user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->role->slug === 'admin';
    }

    /**
     * Get the notifications for the user.
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'notifiable_id')
            ->where('notifiable_type', self::class);
    }

/**
 * Get the device tokens for the user.
 */
public function deviceTokens(): HasMany
{
    return $this->hasMany(UserDeviceToken::class, 'user_id')
        ->where('user_type', 'user');
}


}
