<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\OrderPayment;
use App\Models\WalletTransaction;
use App\Services\Payment\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TapController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function handleCallback(Request $request)
    {
        Log::info('Tap payment callback received', ['data' => $request->all()]);

        $payment = $this->paymentService->getPaymentStatus($request->input('tap_id'));

        if ($payment['status'] === 'CAPTURED') {
            $orderPayment = OrderPayment::where('transaction_id', $payment['id'])->first();

            if ($orderPayment) {
                $orderPayment->update([
                    'status' => 'completed',
                    'response' => $payment
                ]);

                // Deduct from wallet if payment source is wallet
                if ($payment['source']['payment_type'] === 'wallet') {
                    $this->deductFromWallet($orderPayment);
                }

                return response()->json(['success' => true]);
            }
        }

        return response()->json(['success' => false], 400);
    }

    protected function deductFromWallet(OrderPayment $orderPayment)
    {
        $wallet = $orderPayment->order->user->wallet;

        if ($wallet) {
            $wallet->decrement('balance', $orderPayment->amount);

            WalletTransaction::create([
                'wallet_id' => $wallet->id,
                'amount' => $orderPayment->amount,
                'type' => 'debit',
                'reason' => 'order_payment',
                'reference_id' => $orderPayment->order_id,
                'status' => 'completed'
            ]);
        }
    }

    public function handleCharge(Request $request)
    {
        $data = $request->validate([
            'amount' => 'required|numeric',
            'currency' => 'required|string',
            'customer' => 'required|array',
            'customer.first_name' => 'required|string',
            'customer.last_name' => 'required|string',
            'customer.email' => 'required|email',
            'source.id' => 'required|string',
            'metadata.order_id' => 'required|numeric'
        ]);

        $this->paymentService->setProvider('tap');
        $response = $this->paymentService->charge($data);

        if ($response['status'] === 'INITIATED') {
            OrderPayment::create([
                'order_id' => $data['metadata']['order_id'],
                'amount' => $data['amount'],
                'currency' => $data['currency'],
                'payment_method' => 'tap',
                'transaction_id' => $response['id'],
                'status' => 'pending',
                'response' => $response
            ]);

            return response()->json([
                'success' => true,
                'redirect_url' => $response['transaction']['url']
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Payment initiation failed'
        ], 400);
    }
}
