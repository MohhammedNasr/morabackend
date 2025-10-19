<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Authenticatable
{
    use HasFactory, SoftDeletes, HasApiTokens;

    protected $fillable = [
        'user_id',
        'name',
        'contact_name',
        'email',
        'phone',
        'password',
        'address',
        'commercial_record',
        'tax_id',
        'bank_account',
        'iban_number',
        'id_number',
        'bank_id',
        'account_owner_name',
        'website',
        'payment_term_days',
        'is_active',
        'is_verified',
        'verified_at',
        'role_id',
        'verification_code',
        'reset_password_otp',
        'reset_password_otp_expires_at',
        'reset_password_token',
        'reset_password_token_expires_at',
        'description',
        'logo',
        'is_featured',
    ];

    protected $casts = [
        'payment_term_days' => 'integer',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function activeProducts(): BelongsToMany
    {
        return $this->products()->wherePivot('is_active', true);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function categories()
    {
        return $this->hasManyThrough(
            Category::class,
            Product::class,
            'supplier_id',
            'id',
            'id',
            'category_id'
        )->distinct();
    }

    public function representatives(): HasMany
    {
        return $this->hasMany(Representative::class);
    }

    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'notifiable_id')
            ->where('notifiable_type', self::class);
    }

    public function deviceTokens(): HasMany
    {
        return $this->hasMany(UserDeviceToken::class, 'user_id')
            ->where('user_type', 'supplier');
    }
}
