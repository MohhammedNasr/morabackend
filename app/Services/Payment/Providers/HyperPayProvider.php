<?php

namespace App\Services\Payment\Providers;

use App\Services\Payment\Contracts\PaymentProvider;

class HyperPayProvider extends AbstractPaymentProvider
{
    protected string $baseUrl = 'https://eu-test.oppwa.com/';
    protected string $entityId;
    protected string $currency = 'SAR';

    public function __construct(array $config, bool $testMode = true)
    {
        parent::__construct($config, $testMode);

        if (empty($config['entity_id'])) {
            throw new \InvalidArgumentException('HyperPay entity_id is required');
        }

        $this->entityId = $config['entity_id'];
    }

    public function charge(array $data): array
    {

        $requiredFields = [
            'amount',
            'merchantTransactionId',
            'customer.email',
            'billing.street1',
            'billing.city',
            'billing.state',
            'billing.country',
            'billing.postcode',
            'customer.givenName',
            'customer.surname'
        ];
        $this->validateRequiredFields($data, $requiredFields);

        $payload = array_merge([
            'entityId' => $this->entityId,
            'amount' => number_format($data['amount'], 2, '.', ''),
            'currency' => $this->currency,
            'paymentType' => 'DB',
            'testMode' => $this->testMode ? 'EXTERNAL' : 'NONE',
            'customParameters[3DS2_enrolled]' => 'true',
            'integrity' => 'true'
        ], $data);

        return $this->makeRequest($this->baseUrl . 'v1/checkouts', $payload);
    }

    public function refund(string $transactionId, float $amount): array
    {
        $payload = [
            'entityId' => $this->entityId,
            'amount' => number_format($amount, 2, '.', ''),
            'currency' => $this->currency,
            'paymentType' => 'RF'
        ];

        return $this->makeRequest($this->baseUrl . "v1/payments/{$transactionId}", $payload);
    }

    public function getPaymentMethods(): array
    {
        return [
            'VISA' => 'Visa',
            'MASTER' => 'MasterCard',
            'MADA' => 'MADA'
        ];
    }

    public function getPaymentStatus(string $transactionId): array
    {
        return $this->makeRequest($this->baseUrl . "v1/checkouts/{$transactionId}/payment", [
            'entityId' => $this->entityId
        ]);
    }
}
