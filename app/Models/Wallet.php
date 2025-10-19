<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wallet extends Model
{
    use HasFactory , SoftDeletes;

    protected $fillable = [
        'store_id',
        'balance',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class);
    }

    public function hasEnoughBalance(float $amount): bool
    {
        return $this->balance >= $amount;
    }

    public function deposit(float $amount, ?string $description = null, ?Order $order = null): WalletTransaction
    {
        $transaction = $this->transactions()->create([
            'type' => 'deposit',
            'amount' => $amount,
            'balance_after' => $this->balance + $amount,
            'description' => $description,
            'order_id' => $order?->id,
            'reference_number' => $this->generateReferenceNumber(),
        ]);

        $this->increment('balance', $amount);

        return $transaction;
    }

    public function withdraw(float $amount, ?string $description = null, ?Order $order = null): WalletTransaction
    {
        if (!$this->hasEnoughBalance($amount)) {
            throw new \Exception('Insufficient balance');
        }

        $transaction = $this->transactions()->create([
            'type' => 'payment',
            'amount' => $amount,
            'balance_after' => $this->balance - $amount,
            'description' => $description,
            'order_id' => $order?->id,
            'reference_number' => $this->generateReferenceNumber(),
        ]);

        $this->decrement('balance', $amount);

        return $transaction;
    }

    protected function generateReferenceNumber(): string
    {
        return 'TRX-' . strtoupper(uniqid());
    }
}
