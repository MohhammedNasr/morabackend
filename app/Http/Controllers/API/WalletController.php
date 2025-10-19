<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Wallet\ChargeWalletRequest;
use App\Models\Store;
use App\Models\WalletTransaction;
use App\Services\Payment\PaymentService;
use App\Services\Wallet\WalletService;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class WalletController extends Controller
{
    protected $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    public function testHyper(Request $request)
    {
        return $this->walletService->prepareCheckout($request);
    }

    public function charge(ChargeWalletRequest $request)
    {
        $user = $request->user();
        $store = Store::where('owner_id', $user->id)->first();
        $wallet = Wallet::firstOrCreate(
            ['store_id' => $store->id],
            ['balance' => 0],
            ['status' => 'active']
        );

        try {
            $result = $this->walletService->chargeWallet(
                $wallet,
                $request->amount,
                $request->validated(),
                $request,
                'wallet'
            );

            // Handle redirect flow
            if (isset($result['status']) && $result['status'] === 'redirect') {
                return response()->json([
                    'success' => true,
                    'redirect' => true,
                    'redirect_url' => $result['redirect_url'],
                    'transaction_id' => $result['transaction_id']
                ]);
            }

            // Handle direct completion
            return response()->json([
                'success' => true,
                'transaction' => $result,
                'balance' => $wallet->fresh()->balance
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function handlePaymentCallback(Request $request)
    {
        Log::info('Wallet payment callback received', $request->all());

        try {
            $transactionId = $request->input('tap_id');
            if (!$transactionId) {
                throw new \Exception('Missing transaction ID in callback');
            }

            // Get payment status
            $paymentService = new PaymentService('tap');
            $paymentStatus = $paymentService->getPaymentStatus($transactionId);

            // Find and update wallet transaction
            $transaction = WalletTransaction::where('transaction_id', $transactionId)->firstOrFail();

            $transaction->update([
                'status' => $paymentStatus['status'] === 'CAPTURED'
                    ? WalletTransaction::STATUS_COMPLETED
                    : WalletTransaction::STATUS_FAILED
            ]);

            // Update wallet balance if payment succeeded
            if ($paymentStatus['status'] === 'CAPTURED') {
                $transaction->wallet()->increment('balance', $transaction->amount);
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Wallet callback failed', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);
            return response()->json(['success' => false], 400);
        }
    }

    public function chargeWallet(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:255'
        ]);

        return $this->walletService->chargeWalletOLD($request->amount);
    }

    public function getStoreBalance(Request $request)
    {
        return $this->walletService->getStoreBalance();
    }

    public function getWalletTransactions(Request $request)
    {
        return $this->walletService->getWalletTransactions();
    }
}
