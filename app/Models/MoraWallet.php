<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MoraWallet extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'mora_wallet';

    protected $fillable = [
        'balance',
        'total_credited',
        'total_debited',
        'currency',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'total_credited' => 'decimal:2',
        'total_debited' => 'decimal:2',
    ];

    /**
     * Get all transactions for this wallet
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(MoraWalletTransaction::class);
    }

    /**
     * Get the singleton instance of Mora Wallet
     */
    public static function getInstance(): self
    {
        return self::firstOrCreate(
            ['id' => 1],
            [
                'balance' => 0,
                'total_credited' => 0,
                'total_debited' => 0,
                'currency' => 'SAR',
            ]
        );
    }

    /**
     * Add money to Mora wallet (credit)
     */
    public function credit(
        float $amount,
        string $transactionType,
        string $description = null,
        ?BranchBalanceRequest $balanceRequest = null,
        ?User $initiatedBy = null,
        array $metadata = []
    ): MoraWalletTransaction {
        $balanceBefore = $this->balance;
        $balanceAfter = $balanceBefore + $amount;

        $transaction = $this->transactions()->create([
            'type' => 'credit',
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
            'reference_number' => $this->generateReferenceNumber(),
            'transaction_type' => $transactionType,
            'description' => $description,
            'balance_request_id' => $balanceRequest?->id,
            'initiated_by' => $initiatedBy?->id,
            'metadata' => $metadata,
        ]);

        $this->increment('balance', $amount);
        $this->increment('total_credited', $amount);

        return $transaction;
    }

    /**
     * Deduct money from Mora wallet (debit)
     */
    public function debit(
        float $amount,
        string $transactionType,
        string $description = null,
        ?BranchBalanceRequest $balanceRequest = null,
        ?User $initiatedBy = null,
        array $metadata = []
    ): MoraWalletTransaction {
        if ($this->balance < $amount) {
            throw new \Exception('Insufficient balance in Mora wallet');
        }

        $balanceBefore = $this->balance;
        $balanceAfter = $balanceBefore - $amount;

        $transaction = $this->transactions()->create([
            'type' => 'debit',
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
            'reference_number' => $this->generateReferenceNumber(),
            'transaction_type' => $transactionType,
            'description' => $description,
            'balance_request_id' => $balanceRequest?->id,
            'initiated_by' => $initiatedBy?->id,
            'metadata' => $metadata,
        ]);

        $this->decrement('balance', $amount);
        $this->increment('total_debited', $amount);

        return $transaction;
    }

    /**
     * Generate unique reference number for transaction
     */
    protected function generateReferenceNumber(): string
    {
        return 'MORA-' . strtoupper(uniqid()) . '-' . now()->format('Ymd');
    }

    /**
     * Check if wallet has enough balance
     */
    public function hasEnoughBalance(float $amount): bool
    {
        return $this->balance >= $amount;
    }
}
