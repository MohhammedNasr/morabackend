<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\OrderPayment;
use App\Models\Store;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Services\Payment\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderPaymentController extends Controller
{
    public function pay(OrderPayment $orderPayment, Request $request)
    {
        // Check if payment is already paid
        if ($orderPayment->status === OrderPayment::STATUS_PAID) {
            return response()->json([
                'success' => false,
                'message' => 'Payment already completed'
            ], 400);
        }

        try {
            // Check if wallet payment is requested
            if ($request->input('use_wallet', false)) {
                $user = $request->user();
                $store = Store::where('owner_id', $user->id)->first();

                $wallet = Wallet::where('store_id', $store->id)->first();

                if (!$wallet) {
                    throw new \Exception('Wallet not found');
                }

                // Handle wallet payment (full or partial)
                $remainingAmount = $orderPayment->amount - $wallet->balance;
                $walletAmount = min($orderPayment->amount, $wallet->balance);

                DB::transaction(function () use ($wallet, $orderPayment, $walletAmount, $remainingAmount) {
                    // Deduct available balance from wallet
                    if ($walletAmount > 0) {
                        $wallet->decrement('balance', $walletAmount);

                        // Create wallet transaction
                        $wallet->transactions()->create([
                            'amount' => $walletAmount,
                            'type' => 'debit',
                            'status' => WalletTransaction::STATUS_COMPLETED,
                            'description' => 'Partial order payment',
                            'reference_number' => 'ORDER-' . $orderPayment->order_id,
                            'order_id' => $orderPayment->order_id,
                            'order_payment_id' => $orderPayment->id
                        ]);
                    }

                    if ($remainingAmount <= 0) {
                        // Full payment from wallet
                        $orderPayment->update([
                            'status' => OrderPayment::STATUS_PAID,
                            'payment_method' => 'wallet',
                            'transaction_number' => 'WALLET-' . time()
                        ]);
                        return response()->json([
                            'success' => true,
                            'message' => 'Payment completed using wallet balance'
                        ]);
                    } else {
                        // Partial payment - proceed with remaining amount
                        $orderPayment->update([
                            'amount' => $remainingAmount,
                            'notes' => 'Partial payment (' . $walletAmount . ') deducted from wallet'
                        ]);
                    }
                });
            }

            // Proceed with normal payment flow if wallet not used or insufficient balance
            $paymentService = new PaymentService('tap', 'order');

            $chargeData = [
                'amount' => $orderPayment->amount,
                'currency' => 'SAR',
                'customer' => [
                    'first_name' => $request->user()->name,
                    'last_name' => $request->user()->name,
                    'email' => $request->user()->email
                ],
                'source' => ['id' => 'src_all'],
                'redirect' => [
                    'url' => config('services.tap.redirect_url')
                ],
                'metadata' => [
                    'order_payment_id' => $orderPayment->id,
                    'order_id' => $orderPayment->order_id
                ]
            ];

            $paymentResult = $paymentService->charge($chargeData);

            if (!isset($paymentResult['status'])) {
                throw new \Exception('Invalid payment response');
            }

            if ($paymentResult['status'] === 'INITIATED') {
                // Store transaction ID in order payment
                $orderPayment->update([
                    'transaction_number' => $paymentResult['id'],
                    'status' => 'pending'
                ]);

                return response()->json([
                    'success' => true,
                    'redirect_url' => $paymentResult['transaction']['url'],
                    'transaction_id' => $paymentResult['id']
                ]);
            }

            throw new \Exception('Payment failed: ' . ($paymentResult['message'] ?? 'Unknown error'));
        } catch (\Exception $e) {
            Log::error('Order payment failed', [
                'error' => $e->getMessage(),
                'order_payment_id' => $orderPayment->id
            ]);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    // with json response 

    // public function webhook(Request $request)
    // {
    //     Log::info('Payment webhook received', $request->all());

    //     try {
    //         $transactionId = $request->input('tap_id');
    //         if (!$transactionId) {
    //             throw new \Exception('Missing transaction ID in webhook');
    //         }

    //         // Get and validate payment status from Tap
    //         $paymentService = new PaymentService('tap');
    //         $paymentStatus = $paymentService->getPaymentStatus($transactionId);
    //         if (!is_array($paymentStatus) || !isset($paymentStatus['status'])) {
    //             throw new \Exception('Invalid payment status response format');
    //         }

    //         // dd($paymentStatus);

    //         if (in_array($paymentStatus['status'], ['FAILED', 'DECLINED']) && isset($paymentStatus['response']['code'])) {
    //             $errorCode = $paymentStatus['response']['code'];

    //             Log::warning('Payment processing failed', [
    //                 'code' => $errorCode,
    //                 'message' => $paymentStatus['response']['message'],
    //                 'transaction_id' => $transactionId,
    //                 'status' => $paymentStatus['status']
    //             ]);

    //             return response()->json([
    //                 'success' => false,
    //                 'code' => $errorCode,
    //                 'reason' => $paymentStatus['response']['message']
    //             ]);
    //         }

    //         // Check if this is a wallet payment
    //         if (isset($paymentStatus['metadata']['wallet_id'])) {
    //             if (!isset($paymentStatus['metadata']['wallet_id'])) {
    //                 throw new \Exception('Missing wallet_id in payment metadata');
    //             }
    //             // Handle wallet payment
    //             $transaction = WalletTransaction::where('transaction_id', $transactionId)->firstOrFail();

    //             $transaction->update([
    //                 'status' => strtoupper($paymentStatus['status']) === 'CAPTURED'
    //                     ? WalletTransaction::STATUS_COMPLETED
    //                     : WalletTransaction::STATUS_FAILED
    //             ]);

    //             // Update wallet balance if payment succeeded
    //             if (strtoupper($paymentStatus['status']) === 'CAPTURED') {
    //                 $transaction->wallet()->increment('balance', $transaction->amount);
    //                 return response()->json(['success' => true, 'status' => $paymentStatus['status']]);
    //             } else {
    //                 return response()->json(['success' => false, 'code' => $paymentStatus['response']['code'], 'reason' => $paymentStatus['response']['message']]);
    //             }
    //         } else {
    //             // Handle order payment
    //             $orderPayment = OrderPayment::where('transaction_number', $transactionId)->first();
    //             if ($orderPayment) {
    //                 if (
    //                     strtoupper($paymentStatus['status']) === 'CAPTURED' ||
    //                     (isset($paymentStatus['response']['code']) && $paymentStatus['response']['code'] === '000')
    //                 ) {
    //                     $orderPayment->update([
    //                         'status' => 'captured',
    //                         'payment_method' => $paymentStatus['source']['payment_method'] ?? null
    //                     ]);
    //                     return response()->json(['success' => true]);
    //                 } else {
    //                     return response()->json(['success' => false, 'code' => $paymentStatus['response']['code'], 'reason' => $paymentStatus['response']['message']]);
    //                 }
    //             }
    //         }

    //         // If we get here, no matching wallet or order payment was found
    //         Log::error('Webhook received for unknown transaction', [
    //             'transaction_id' => $transactionId,
    //             'payment_status' => $paymentStatus
    //         ]);
    //         return response()->json(['success' => false, 'message' => 'No matching transaction found'], 404);
    //     } catch (\Exception $e) {
    //         Log::error('Webhook processing failed', [
    //             'error' => $e->getMessage(),
    //             'request' => $request->all()
    //         ]);
    //         return response()->json(['success' => false], 400);
    //     }
    // }
    // with redirect url 
    public function webhook(Request $request)
    {
        Log::info('Payment webhook received', $request->all());
        $url = "https://moora.com.sa/payment";
        try {
            $transactionId = $request->input('tap_id');
            if (!$transactionId) {
                throw new \Exception('Missing transaction ID in webhook');
            }

            // Get and validate payment status from Tap
            $paymentService = new PaymentService('tap');
            $paymentStatus = $paymentService->getPaymentStatus($transactionId);
            if (!is_array($paymentStatus) || !isset($paymentStatus['status'])) {
                throw new \Exception('Invalid payment status response format');
            }

            // dd($paymentStatus);

            if (in_array($paymentStatus['status'], ['FAILED', 'DECLINED']) && isset($paymentStatus['response']['code'])) {
                $errorCode = $paymentStatus['response']['code'];

                Log::warning('Payment processing failed', [
                    'code' => $errorCode,
                    'message' => $paymentStatus['response']['message'],
                    'transaction_id' => $transactionId,
                    'status' => $paymentStatus['status']
                ]);

                // return response()->json([
                //     'success' => false,
                //     'code' => $errorCode,
                //     'reason' => $paymentStatus['response']['message']
                // ]);

                return redirect($url . '?status=failure&code=' . $paymentStatus['response']['code'] . '&reason=' . urlencode($paymentStatus['response']['message']));
            }

            // Check if this is a wallet payment
            if (isset($paymentStatus['metadata']['wallet_id'])) {
                if (!isset($paymentStatus['metadata']['wallet_id'])) {
                    throw new \Exception('Missing wallet_id in payment metadata');
                }
                // Handle wallet payment
                $transaction = WalletTransaction::where('transaction_id', $transactionId)->firstOrFail();

                $transaction->update([
                    'status' => strtoupper($paymentStatus['status']) === 'CAPTURED'
                        ? WalletTransaction::STATUS_COMPLETED
                        : WalletTransaction::STATUS_FAILED
                ]);

                // Update wallet balance if payment succeeded
                if (strtoupper($paymentStatus['status']) === 'CAPTURED') {
                    $transaction->wallet()->increment('balance', $transaction->amount);
                    return redirect($url . '?status=success');
                } else {
                    return redirect($url . '?status=failure&code=' . $paymentStatus['response']['code'] . '&reason=' . urlencode($paymentStatus['response']['message']));
                }
            } else {
                // Handle order payment
                $orderPayment = OrderPayment::where('transaction_number', $transactionId)->first();
                if ($orderPayment) {
                    if (
                        strtoupper($paymentStatus['status']) === 'CAPTURED' ||
                        (isset($paymentStatus['response']['code']) && $paymentStatus['response']['code'] === '000')
                    ) {
                        $orderPayment->update([
                            'status' => OrderPayment::STATUS_PAID,
                            'payment_method' => $paymentStatus['source']['payment_method'] ?? null
                        ]);
                        return redirect($url . '?status=success');
                    } else {
                        return redirect($url . '?status=failure&code=' . $paymentStatus['response']['code'] . '&reason=' . urlencode($paymentStatus['response']['message']));
                    }
                }
            }
            return redirect($url . '?status=failure&message=' . urlencode('No matching transaction found'));

            // If we get here, no matching wallet or order payment was found
            Log::error('Webhook received for unknown transaction', [
                'transaction_id' => $transactionId,
                'payment_status' => $paymentStatus
            ]);
        } catch (\Exception $e) {
            Log::error('Webhook processing failed', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);
            return redirect($url . '?status=failure');
        }
    }
}
