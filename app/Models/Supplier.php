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
        'business_name',
        'contact_name',
        'email',
        'phone',
        'password',
        'address',
        'city',
        'country',
        'commercial_record',
        'commercial_registration',
        'tax_id',
        'bank_account',
        'account_number',
        'iban_number',
        'iban',
        'bank_name',
        'id_number',
        'bank_id',
        'account_owner_name',
        'beneficiary_name',
        'website',
        'payment_term_days',
        'settlement_frequency',
        'settlement_day',
        'is_active',
        'is_verified',
        'verified_at',
        'verified_by',
        'role_id',
        'verification_code',
        'reset_password_otp',
        'reset_password_otp_expires_at',
        'reset_password_token',
        'reset_password_token_expires_at',
        'description',
        'logo',
        'is_featured',
        'notify_on_transaction',
        'notify_on_settlement',
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

    public function subOrders(): HasMany
    {
        return $this->hasMany(SubOrder::class);
    }
    
    public function orders()
    {
        return $this->hasManyThrough(
            Order::class,
            SubOrder::class,
            'supplier_id',
            'id',
            'id',
            'order_id'
        );
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

    /**
     * Get all transactions for this supplier (portal feature)
     */
    public function supplierTransactions(): HasMany
    {
        return $this->hasMany(SupplierTransaction::class);
    }

    /**
     * Get all settlements for this supplier (portal feature)
     */
    public function settlements(): HasMany
    {
        return $this->hasMany(SupplierSettlement::class);
    }

    /**
     * Get stores that have purchased from this supplier
     */
    public function customerStores()
    {
        return $this->hasManyThrough(
            Store::class,
            SupplierTransaction::class,
            'supplier_id',
            'id',
            'id',
            'store_id'
        )->distinct();
    }
}
