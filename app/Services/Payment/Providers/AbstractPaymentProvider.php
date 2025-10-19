<?php

namespace App\Services\Payment\Providers;

use App\Services\Payment\Contracts\PaymentProvider;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

abstract class AbstractPaymentProvider implements PaymentProvider
{
    protected array $config;
    protected bool $testMode;

    public function __construct(array $config, bool $testMode = true)
    {
        $this->config = $config;
        $this->testMode = $testMode;
    }

    protected function makeRequest(string $url, array $data): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->config['access_token'],
                'Content-Type' => 'application/x-www-form-urlencoded'
            ])->asForm()->post($url, $data);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Payment request failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    protected function validateRequiredFields(array $data, array $requiredFields): void
    {
        foreach ($requiredFields as $field) {
            $value = $data;
            $parts = explode('.', $field);

            foreach ($parts as $part) {
                if (!is_array($value) || !array_key_exists($part, $value)) {
                    throw new \InvalidArgumentException("Missing required field: {$field}");
                }
                $value = $value[$part];
            }
        }
    }
}
