<?php

namespace App\Services\Payment\Providers;

use App\Services\Payment\Contracts\PaymentProvider;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TapProvider extends AbstractPaymentProvider
{
    protected $paymentType;

    public function __construct(array $config, bool $isSandbox, string $paymentType = 'order')
    {
        parent::__construct($config, $isSandbox);
        $this->paymentType = $paymentType;
    }

    public function charge(array $data): array
    {
        $this->validateRequiredFields($data, [
            'amount',
            'currency',
            'customer',
            'customer.first_name',
            'customer.last_name',
            'customer.email',
            'source.id'
        ]);

        $payload = [
            'amount' => $data['amount'],
            'currency' => $data['currency'] ?? $this->config['currency'],
            'customer' => [
                'first_name' => $data['customer']['first_name'],
                'last_name' => $data['customer']['last_name'],
                'email' => $data['customer']['email'],
            ],
            'source' => [
                'id' => $data['source']['id']
            ],
            'redirect' => [
                'url' => $this->paymentType === 'wallet'
                    ? $this->config['wallet_callback_url']
                    : $this->config['redirect_url']
            ],
            'metadata' => $data['metadata'] ?? []
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->config['secret_key'],
                'Content-Type' => 'application/json'
            ])->post($this->config['base_url'] . 'charges', $payload);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Tap charge failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function refund(string $transactionId, float $amount): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->config['secret_key'],
                'Content-Type' => 'application/json'
            ])->post($this->config['base_url'] . "refunds", [
                'charge_id' => $transactionId,
                'amount' => $amount,
                'currency' => $this->config['currency'],
                'reason' => 'requested_by_customer'
            ]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Tap refund failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function getPaymentMethods(): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->config['secret_key'],
                'Content-Type' => 'application/json'
            ])->get($this->config['base_url'] . 'payment_methods');

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Failed to get Tap payment methods', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function getPaymentStatus(string $transactionId): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->config['secret_key'],
                'Content-Type' => 'application/json'
            ])->get($this->config['base_url'] . "charges/{$transactionId}");

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Failed to get Tap payment status', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}
