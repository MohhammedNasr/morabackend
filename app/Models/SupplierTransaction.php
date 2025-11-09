<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupplierTransaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'supplier_id',
        'store_id',
        'order_id',
        'transaction_reference',
        'amount',
        'description',
        'items',
        'status',
        'transaction_date',
        'settlement_date',
        'settlement_id',
        'metadata',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_date' => 'datetime',
        'settlement_date' => 'datetime',
        'metadata' => 'array',
    ];

    // Status constants
    public const STATUS_PENDING_SETTLEMENT = 'pending_settlement';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_SETTLED = 'settled';
    public const STATUS_CANCELLED = 'cancelled';

    /**
     * Get the supplier for this transaction
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Get the store (customer) for this transaction
     */
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * Get the order associated with this transaction
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the settlement this transaction belongs to
     */
    public function settlement(): BelongsTo
    {
        return $this->belongsTo(SupplierSettlement::class, 'settlement_id');
    }

    /**
     * Scope for pending settlement transactions
     */
    public function scopePendingSettlement($query)
    {
        return $query->where('status', self::STATUS_PENDING_SETTLEMENT);
    }

    /**
     * Scope for settled transactions
     */
    public function scopeSettled($query)
    {
        return $query->where('status', self::STATUS_SETTLED);
    }

    /**
     * Generate unique transaction reference
     */
    public static function generateReference(): string
    {
        return 'SPTXN-' . strtoupper(uniqid()) . '-' . now()->format('Ymd');
    }
}
