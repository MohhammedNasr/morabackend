<?php

namespace App\Services\Payment;

use App\Services\Payment\Providers\HyperPayProvider;
use InvalidArgumentException;

class PaymentService
{
    protected $provider;

    protected $paymentType;

    public function __construct(string $providerName = 'hyperpay', string $paymentType = 'order')
    {
        $this->paymentType = $paymentType;
        $this->setProvider($providerName);
    }

    public function setProvider(string $providerName): self
    {
        switch ($providerName) {
            case 'hyperpay':
                $hyperpayConfig = [
                    'access_token' => config('services.hyperpay.access_token'),
                    'entity_id' => config('services.hyperpay.entity_id')
                ];

                if (empty($hyperpayConfig['access_token']) || empty($hyperpayConfig['entity_id'])) {
                    throw new \RuntimeException(__('api.config_incomplete', ['provider' => 'HyperPay']));
                }

                $this->provider = new HyperPayProvider($hyperpayConfig, config('app.env') !== 'production');
                break;
            case 'tap':
                $tapConfig = [
                    'secret_key' => config('services.tap.secret_key'),
                    'public_key' => config('services.tap.public_key'),
                    'base_url' => config('services.tap.base_url'),
                    'redirect_url' => config('services.tap.redirect_url'),
                    'currency' => config('services.tap.currency'),
                    'merchant_id' => config('services.tap.merchant_id')
                ];

                if (empty($tapConfig['secret_key']) || empty($tapConfig['merchant_id'])) {
                    throw new \RuntimeException(__('api.config_incomplete', ['provider' => 'Tap']));
                }

                $this->provider = new \App\Services\Payment\Providers\TapProvider($tapConfig, config('app.env') !== 'production');
                break;
            default:
                throw new InvalidArgumentException(__('api.unsupported_provider', ['provider' => $providerName]));
        }

        return $this;
    }

    public function charge(array $data): array
    {
        return $this->provider->charge($data);
    }

    public function refund(string $transactionId, float $amount): array
    {
        return $this->provider->refund($transactionId, $amount);
    }

    public function getPaymentMethods(): array
    {
        return $this->provider->getPaymentMethods();
    }

    public function getPaymentStatus(string $transactionId): array
    {
        return $this->provider->getPaymentStatus($transactionId);
    }
}
