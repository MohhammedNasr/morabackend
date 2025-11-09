<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class SupplierPaymentRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'supplier_id',
        'request_code',
        'amount',
        'description',
        'status',
        'paid_by_store_id',
        'paid_at',
        'transaction_id',
        'expires_at',
        'metadata',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'expires_at' => 'datetime',
        'paid_at' => 'datetime',
        'metadata' => 'array',
    ];

    // Status constants
    public const STATUS_PENDING = 'pending';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_EXPIRED = 'expired';
    public const STATUS_CANCELLED = 'cancelled';

    /**
     * Get the supplier who created this request
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Get the store that paid this request
     */
    public function paidByStore(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'paid_by_store_id');
    }

    /**
     * Get the transaction created when this was paid
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(SupplierTransaction::class, 'transaction_id');
    }

    /**
     * Generate a unique 12-character alphanumeric code
     */
    public static function generateCode(): string
    {
        do {
            // Generate a readable code (no confusing chars like 0, O, 1, I, l)
            $characters = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';
            $code = '';
            for ($i = 0; $i < 12; $i++) {
                $code .= $characters[rand(0, strlen($characters) - 1)];
            }
            
            // Format as XXX-XXX-XXX-XXX for readability
            $code = substr($code, 0, 3) . '-' . substr($code, 3, 3) . '-' . substr($code, 6, 3) . '-' . substr($code, 9, 3);
            
        } while (self::where('request_code', $code)->exists());

        return $code;
    }

    /**
     * Check if request is expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast() && $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if request can be paid
     */
    public function canBePaid(): bool
    {
        return $this->status === self::STATUS_PENDING && !$this->isExpired();
    }

    /**
     * Mark as expired
     */
    public function markAsExpired(): void
    {
        $this->update(['status' => self::STATUS_EXPIRED]);
    }

    /**
     * Mark as completed
     */
    public function markAsCompleted(Store $store, SupplierTransaction $transaction): void
    {
        $this->update([
            'status' => self::STATUS_COMPLETED,
            'paid_by_store_id' => $store->id,
            'transaction_id' => $transaction->id,
            'paid_at' => now(),
        ]);
    }

    /**
     * Scope for pending requests
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope for active (not expired and pending)
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_PENDING)
                     ->where('expires_at', '>', now());
    }
}
