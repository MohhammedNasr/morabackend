<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupplierSettlement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'supplier_id',
        'settlement_reference',
        'period_start',
        'period_end',
        'total_amount',
        'transaction_count',
        'status',
        'scheduled_payment_date',
        'actual_payment_date',
        'payment_reference',
        'payment_method',
        'bank_account',
        'payment_notes',
        'processed_by',
        'processed_at',
        'metadata',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'total_amount' => 'decimal:2',
        'scheduled_payment_date' => 'date',
        'actual_payment_date' => 'datetime',
        'processed_at' => 'datetime',
        'metadata' => 'array',
    ];

    // Status constants
    public const STATUS_PENDING = 'pending';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_FAILED = 'failed';

    /**
     * Get the supplier for this settlement
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Get all transactions in this settlement
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(SupplierTransaction::class, 'settlement_id');
    }

    /**
     * Get the user who processed this settlement
     */
    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * Scope for pending settlements
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope for completed settlements
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    /**
     * Generate unique settlement reference
     */
    public static function generateReference(): string
    {
        return 'SETTLE-' . strtoupper(uniqid()) . '-' . now()->format('Ymd');
    }

    /**
     * Mark settlement as completed
     */
    public function markAsCompleted(User $processor, string $paymentReference = null): void
    {
        $this->update([
            'status' => self::STATUS_COMPLETED,
            'actual_payment_date' => now(),
            'payment_reference' => $paymentReference,
            'processed_by' => $processor->id,
            'processed_at' => now(),
        ]);

        // Update all related transactions
        $this->transactions()->update([
            'status' => SupplierTransaction::STATUS_SETTLED,
            'settlement_date' => now(),
        ]);
    }
}
