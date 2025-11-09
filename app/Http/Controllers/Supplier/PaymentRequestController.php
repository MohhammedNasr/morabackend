<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\SupplierPaymentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentRequestController extends Controller
{
    /**
     * Create a new payment request
     */
    public function store(Request $request)
    {
        $supplier = $request->user();

        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1|max:1000000',
            'description' => 'nullable|string|max:500',
            'expires_in_minutes' => 'nullable|integer|min:5|max:1440', // 5 mins to 24 hours
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $expiresInMinutes = $request->get('expires_in_minutes', 60); // Default 1 hour

            $paymentRequest = SupplierPaymentRequest::create([
                'supplier_id' => $supplier->id,
                'request_code' => SupplierPaymentRequest::generateCode(),
                'amount' => $request->amount,
                'description' => $request->description,
                'expires_at' => now()->addMinutes($expiresInMinutes),
                'status' => SupplierPaymentRequest::STATUS_PENDING,
            ]);

            return response()->json([
                'message' => 'Payment request created successfully',
                'payment_request' => [
                    'id' => $paymentRequest->id,
                    'request_code' => $paymentRequest->request_code,
                    'amount' => (float) $paymentRequest->amount,
                    'description' => $paymentRequest->description,
                    'status' => $paymentRequest->status,
                    'expires_at' => $paymentRequest->expires_at->toIso8601String(),
                    'created_at' => $paymentRequest->created_at->toIso8601String(),
                ],
            ], 201);
        } catch (\Exception $e) {
            \Log::error('Payment request creation error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to create payment request',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all payment requests for supplier
     */
    public function index(Request $request)
    {
        $supplier = $request->user();

        try {
            $perPage = $request->get('per_page', 20);
            $status = $request->get('status');

            $query = SupplierPaymentRequest::where('supplier_id', $supplier->id)
                ->with('paidByStore:id,name')
                ->orderBy('created_at', 'desc');

            if ($status) {
                $query->where('status', $status);
            }

            $paymentRequests = $query->paginate($perPage);

            $paymentRequests->getCollection()->transform(function($pr) {
                return [
                    'id' => $pr->id,
                    'request_code' => $pr->request_code,
                    'amount' => (float) $pr->amount,
                    'description' => $pr->description,
                    'status' => $pr->status,
                    'paid_by' => $pr->paidByStore ? [
                        'id' => $pr->paidByStore->id,
                        'name' => $pr->paidByStore->name,
                    ] : null,
                    'paid_at' => $pr->paid_at?->toIso8601String(),
                    'expires_at' => $pr->expires_at?->toIso8601String(),
                    'is_expired' => $pr->isExpired(),
                    'created_at' => $pr->created_at->toIso8601String(),
                ];
            });

            return response()->json($paymentRequests);
        } catch (\Exception $e) {
            \Log::error('Payment requests fetch error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to fetch payment requests',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get single payment request details
     */
    public function show(Request $request, $id)
    {
        $supplier = $request->user();

        try {
            $paymentRequest = SupplierPaymentRequest::where('supplier_id', $supplier->id)
                ->where('id', $id)
                ->with(['paidByStore', 'transaction'])
                ->firstOrFail();

            return response()->json([
                'id' => $paymentRequest->id,
                'request_code' => $paymentRequest->request_code,
                'amount' => (float) $paymentRequest->amount,
                'description' => $paymentRequest->description,
                'status' => $paymentRequest->status,
                'paid_by' => $paymentRequest->paidByStore ? [
                    'id' => $paymentRequest->paidByStore->id,
                    'name' => $paymentRequest->paidByStore->name,
                    'phone' => $paymentRequest->paidByStore->phone,
                ] : null,
                'paid_at' => $paymentRequest->paid_at?->toIso8601String(),
                'transaction' => $paymentRequest->transaction ? [
                    'id' => $paymentRequest->transaction->id,
                    'reference' => $paymentRequest->transaction->transaction_reference,
                ] : null,
                'expires_at' => $paymentRequest->expires_at?->toIso8601String(),
                'is_expired' => $paymentRequest->isExpired(),
                'can_be_paid' => $paymentRequest->canBePaid(),
                'created_at' => $paymentRequest->created_at->toIso8601String(),
            ]);
        } catch (\Exception $e) {
            \Log::error('Payment request show error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Payment request not found',
                'message' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Cancel a payment request
     */
    public function cancel(Request $request, $id)
    {
        $supplier = $request->user();

        try {
            $paymentRequest = SupplierPaymentRequest::where('supplier_id', $supplier->id)
                ->where('id', $id)
                ->where('status', SupplierPaymentRequest::STATUS_PENDING)
                ->firstOrFail();

            $paymentRequest->update(['status' => SupplierPaymentRequest::STATUS_CANCELLED]);

            return response()->json([
                'message' => 'Payment request cancelled successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Payment request cancel error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to cancel payment request',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
