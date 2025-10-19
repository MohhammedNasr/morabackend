<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Store extends Model
{
    use HasFactory, SoftDeletes;

    const TYPE_HYPERMARKET = 'hypermarket';
    const TYPE_SUPERMARKET = 'supermarket';
    const TYPE_RESTAURANT = 'restaurant';

    const TYPES = [
        self::TYPE_HYPERMARKET,
        self::TYPE_SUPERMARKET,
        self::TYPE_RESTAURANT,
    ];

    protected $fillable = [
        'name',
        'type',
        'email',
        'phone',
        'commercial_record',
        'tax_number',
        'tax_record',
        'id_number',
        'is_verified',
        'credit_balance',
        'owner_id',
        'auto_verify_order'
    ];
    protected $casts = [
        'is_verified' => 'boolean',
        'credit_balance' => 'decimal:2',
    ];

    protected $appends = [
        'branches_count',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'store_users')
            ->withPivot('is_primary')
            ->withTimestamps();
    }

    public function owner(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'store_users')
            ->wherePivot('is_primary', true)
            ->withTimestamps();
    }
    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    public function branches(): HasMany
    {
        return $this->hasMany(StoreBranch::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function getBranchesCountAttribute(): int
    {
        return $this->branches()->count();
    }

    public function hasAvailableCredit(float $amount): bool
    {
        return $this->credit_balance >= $amount;
    }

    public function scopeActive($query)
    {
        return $query->where('is_verified', true);
    }
}
