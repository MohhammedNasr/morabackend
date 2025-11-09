<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MoraWalletTransaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'mora_wallet_id',
        'type',
        'amount',
        'balance_before',
        'balance_after',
        'reference_number',
        'transaction_type',
        'description',
        'balance_request_id',
        'initiated_by',
        'metadata',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_before' => 'decimal:2',
        'balance_after' => 'decimal:2',
        'metadata' => 'array',
    ];

    // Transaction types
    public const TYPE_CREDIT = 'credit';
    public const TYPE_DEBIT = 'debit';

    // Transaction type categories
    public const TRANSACTION_TYPE_BALANCE_APPROVAL = 'balance_approval';
    public const TRANSACTION_TYPE_LOAN_REPAYMENT = 'loan_repayment';
    public const TRANSACTION_TYPE_MANUAL_ADJUSTMENT = 'manual_adjustment';

    /**
     * Get the wallet this transaction belongs to
     */
    public function moraWallet(): BelongsTo
    {
        return $this->belongsTo(MoraWallet::class);
    }

    /**
     * Get the balance request associated with this transaction
     */
    public function balanceRequest(): BelongsTo
    {
        return $this->belongsTo(BranchBalanceRequest::class, 'balance_request_id');
    }

    /**
     * Get the user who initiated this transaction
     */
    public function initiator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'initiated_by');
    }

    /**
     * Check if transaction is a credit (money in)
     */
    public function isCredit(): bool
    {
        return $this->type === self::TYPE_CREDIT;
    }

    /**
     * Check if transaction is a debit (money out)
     */
    public function isDebit(): bool
    {
        return $this->type === self::TYPE_DEBIT;
    }

    /**
     * Scope for credit transactions
     */
    public function scopeCredits($query)
    {
        return $query->where('type', self::TYPE_CREDIT);
    }

    /**
     * Scope for debit transactions
     */
    public function scopeDebits($query)
    {
        return $query->where('type', self::TYPE_DEBIT);
    }

    /**
     * Scope for balance approval transactions
     */
    public function scopeBalanceApprovals($query)
    {
        return $query->where('transaction_type', self::TRANSACTION_TYPE_BALANCE_APPROVAL);
    }

    /**
     * Scope for loan repayment transactions
     */
    public function scopeLoanRepayments($query)
    {
        return $query->where('transaction_type', self::TRANSACTION_TYPE_LOAN_REPAYMENT);
    }
}
