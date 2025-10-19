<?php

namespace App\Services\Payment\Contracts;

interface PaymentProvider
{
    public function charge(array $data): array;
    public function refund(string $transactionId, float $amount): array;
    public function getPaymentMethods(): array;
    public function getPaymentStatus(string $transactionId): array;
}
