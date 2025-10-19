<?php

namespace App\Services\Wallet;

use App\Models\Store;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Services\BaseService;
use App\Services\Payment\PaymentService;
use Devinweb\LaravelHyperpay\Facades\Hyperpay;
use Devinweb\LaravelHyperpay\Facades\LaravelHyperpay;
use Illuminate\Http\Client\Request;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WalletService extends BaseService
{

    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function prepareCheckout(HttpRequest $request)
    {
        $trackable = [
            'product_id' => 'bc842310-371f-49d1-b479-ad4b387f6630',
            'product_type' => 't-shirt'
        ];
        $user = User::first();
        $amount = 10;
        $brand = 'VISA'; // MASTER OR MADA

        return LaravelHyperpay::checkout($trackable, $user, $amount, $brand, $request);
    }

    public function chargeWallet(Wallet $wallet, float $amount, array $paymentData, $request, string $paymentType = 'wallet')
    {
        return DB::transaction(function () use ($wallet, $amount, $paymentData, $request, $paymentType) {
            // Use Tap payment provider
            $paymentService = new PaymentService('tap', $paymentType);

            $chargeData = [
                'amount' => $amount,
                'currency' => 'SAR',
                'customer' => [
                    'first_name' => $request->user()->name,
                    'last_name' => $request->user()->name, // Using name as last name since last name isn't stored
                    'email' => $request->user()->email
                ],
                'source' => [
                    'id' => 'src_all' // Use all available payment methods
                ],
                'metadata' => [
                    'wallet_id' => $wallet->id,
                    'store_id' => $wallet->store_id
                ]
            ];

            try {
                $paymentResult = $paymentService->charge($chargeData);

                // Handle INITIATED status by checking payment status
                if (!isset($paymentResult['status'])) {
                    Log::error('Tap payment missing status', ['response' => $paymentResult]);
                    throw new \Exception('Payment failed: Invalid response from payment gateway');
                }

                if ($paymentResult['status'] === 'INITIATED') {
                    // For INITIATED status, create pending transaction and return redirect URL
                    if (!isset($paymentResult['transaction']['url'])) {
                        throw new \Exception('Payment initiation failed: Missing redirect URL');
                    }

                    // Create pending transaction
                    $transaction = $wallet->transactions()->create([
                        'amount' => $amount,
                        'type' => 'deposit',
                        'status' => WalletTransaction::STATUS_PENDING,
                        'payment_reference' => $paymentResult['id'],
                        'transaction_id' => $paymentResult['id'],
                        'metadata' => $paymentResult
                    ]);

                    return [
                        'status' => 'redirect',
                        'redirect_url' => $paymentResult['transaction']['url'],
                        'transaction_id' => $paymentResult['id']
                    ];
                }

                if (!in_array($paymentResult['status'], ['CAPTURED', 'AUTHORIZED'])) {
                    Log::error('Tap payment failed response', ['response' => $paymentResult]);
                    $errorMsg = $paymentResult['message'] ??
                               ($paymentResult['error']['description'] ??
                               ($paymentResult['response']['message'] ?? 'Payment failed with status: ' . $paymentResult['status']));
                    throw new \Exception($errorMsg);
                }

                // Create wallet transaction
                $transaction = $wallet->transactions()->create([
                    'amount' => $amount,
                    'type' => 'deposit',
                    'status' => $paymentResult['status'] === 'CAPTURED' ?
                        WalletTransaction::STATUS_COMPLETED :
                        WalletTransaction::STATUS_PENDING,
                    'payment_reference' => $paymentResult['id'],
                    'transaction_id' => $paymentResult['id'],
                    'metadata' => $paymentResult
                ]);

                // Update wallet balance
                $wallet->increment('balance', $amount);

                return $transaction;
            } catch (\Exception $e) {
                Log::error('Tap payment failed', [
                    'error' => $e->getMessage(),
                    'wallet_id' => $wallet->id,
                    'amount' => $amount
                ]);
                throw $e;
            }
        });

        // return DB::transaction(function () use ($wallet, $amount, $paymentData,$request) {
        //     // Use laravel-hyperpay package
        //     // $checkoutId = LaravelHyperpay::checkout([
        //     //     'amount' => $amount,
        //     //     'currency' => 'SAR',
        //     //     'payment_type' => 'DB',
        //     //     'customer_email' => "test@gmail.com",
        //     //     'billing_street1' => "Riyadh",
        //     //     'billing_city' => "Riyadh",
        //     //     'billing_country' => "SA",
        //     //     'merchantTransactionId' => 'WALLET-' . $wallet->id . '-' . time()
        //     // ]);


        //     $trackable = [
        //         'product_id' => 'bc842310-371f-49d1-b479-ad4b387f6630',
        //         'product_type' => 't-shirt'
        //     ];
        //     $user = User::first();
        //     $amount = 10;
        //     $brand = 'VISA';; // MASTER OR MADA


        //     $checkoutId = LaravelHyperpay::checkout($trackable, $user, $amount, $brand, $request);


        //     // Get payment status
        //     $paymentResult = LaravelHyperpay::paymentStatus($checkoutId);
        //     Log::debug('HyperPay payment status response', ['response' => $paymentResult]);

        //     if (!isset($paymentResult['result']['code']) || $paymentResult['result']['code'] !== '000.200.000') {
        //         $errorMsg = isset($paymentResult['result']['description'])
        //             ? $paymentResult['result']['description']
        //             : 'Payment failed with unknown error';
        //         throw new \Exception('Payment failed: ' . $errorMsg);
        //     }

        //     if (!isset($paymentResult['id'])) {
        //         throw new \Exception('Invalid HyperPay response: Missing transaction ID');
        //     }

        //     // Create wallet transaction
        //     $transaction = $wallet->transactions()->create([
        //         'amount' => $amount,
        //         'type' => 'deposit',
        //         'status' => 'completed',
        //         'payment_reference' => $paymentResult['id'],
        //         'metadata' => $paymentResult
        //     ]);

        //     // Update wallet balance
        //     $wallet->increment('balance', $amount);

        //     return $transaction;
        // });
    }

    public function chargeWalletOLD($amount)
    {
        // $store = Auth::user()->store;
        $store = Store::where('owner_id', Auth::user()->id)->first();

        if (!$store) {
            return $this->errorResponse(__('api.store_not_found'), 404);
        }

        $wallet = Wallet::firstOrCreate(
            ['store_id' => $store->id],
            ['balance' => 0]
        );

        $wallet->increment('balance', $amount);

        $newBalance = $wallet->balance;

        WalletTransaction::create([
            'wallet_id' => $wallet->id,
            'amount' => $amount,
            'type' => 'credit',
            'description' => 'Wallet charge',
            'balance_after' => $newBalance,
            'reference_number' => \Illuminate\Support\Str::uuid()->toString()
        ]);

            return $this->successResponse([
                'balance' => $wallet->balance
            ], __('api.wallet_balance_retrieved'));
    }

    public function getStoreBalance()
    {
        $store = Store::where('owner_id', Auth::user()->id)->first();

        if (!$store) {
            return $this->errorResponse('Store not found', 404);
        }

        $wallet = Wallet::firstOrCreate(
            ['store_id' => $store->id],
            ['balance' => 0]
        );

        return $this->successResponse([
            'balance' => $wallet->balance
        ]);
    }

    public function getWalletTransactions()
    {
        $store = Store::where('owner_id', Auth::user()->id)->first();

        if (!$store) {
            return $this->errorResponse('Store not found', 404);
        }

        $wallet = Wallet::firstOrCreate(
            ['store_id' => $store->id],
            ['balance' => 0]
        );

        $transactions = WalletTransaction::where('wallet_id', $wallet->id)
            ->orderBy('created_at', 'desc')
            ->get();

            return $this->successResponse([
                'transactions' => $transactions
            ], __('api.wallet_transactions_retrieved'));
    }
}
