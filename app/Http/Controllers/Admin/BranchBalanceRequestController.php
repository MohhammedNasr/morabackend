<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BranchBalanceRequest;
use App\Models\StoreBranch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BranchBalanceRequestController extends Controller
{
    public function __construct()
    {
        // Middleware is handled by routes (auth:sanctum for API, admin for web)
        // $this->middleware('admin');
    }

    /**
     * Get all balance requests for admin review
     */
    public function index(Request $request)
    {
        try {
            $requests = BranchBalanceRequest::with(['storeBranch.store', 'reviewer'])
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($req) {
                    return [
                        'id' => $req->id,
                        'branch_name' => $req->storeBranch?->name ?? 'N/A',
                        'store_name' => $req->storeBranch?->store?->name ?? 'N/A',
                        'requested_balance_limit' => (float) $req->requested_balance_limit,
                        'status' => $req->status,
                        'approved_balance_limit' => $req->approved_balance_limit ? (float) $req->approved_balance_limit : null,
                        'rejection_reason' => $req->rejection_reason,
                        'created_at' => $req->created_at?->format('Y-m-d H:i:s'),
                        'reviewed_at' => $req->reviewed_at?->format('Y-m-d H:i:s'),
                    ];
                });

            return response()->json($requests);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch balance requests',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a single balance request with full details
     */
    public function show($id)
    {
        try {
            $request = BranchBalanceRequest::with(['storeBranch.store', 'reviewer'])
                ->findOrFail($id);

            return response()->json([
                'id' => $request->id,
                'branch' => [
                    'id' => $request->storeBranch?->id ?? null,
                    'name' => $request->storeBranch?->name ?? 'N/A',
                ],
                'store' => [
                    'id' => $request->storeBranch?->store?->id ?? null,
                    'name' => $request->storeBranch?->store?->name ?? 'N/A',
                ],
                'requested_balance_limit' => (float) $request->requested_balance_limit,
                'business_type' => $request->business_type,
                'years_in_business' => $request->years_in_business,
                'average_monthly_revenue' => $request->average_monthly_revenue ? (float) $request->average_monthly_revenue : null,
                'number_of_employees' => $request->number_of_employees,
                'business_description' => $request->business_description,
                'tax_registration_number' => $request->tax_registration_number,
                'commercial_registration_number' => $request->commercial_registration_number,
                'bank_account_number' => $request->bank_account_number,
                'bank_name' => $request->bank_name,
                'iban' => $request->iban,
                'contact_person_name' => $request->contact_person_name,
                'contact_person_phone' => $request->contact_person_phone,
                'contact_person_email' => $request->contact_person_email,
                'contact_person_position' => $request->contact_person_position,
                'documents' => $request->documents,
                'status' => $request->status,
                'admin_notes' => $request->admin_notes,
                'rejection_reason' => $request->rejection_reason,
                'approved_balance_limit' => $request->approved_balance_limit ? (float) $request->approved_balance_limit : null,
                'reviewed_by' => $request->reviewer ? $request->reviewer->name : null,
                'reviewed_at' => $request->reviewed_at?->format('Y-m-d H:i:s'),
                'created_at' => $request->created_at?->format('Y-m-d H:i:s'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch balance request',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Approve a balance request
     */
    public function approve(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'approved_balance_limit' => 'required|numeric|min:0',
            'admin_notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $balanceRequest = BranchBalanceRequest::findOrFail($id);

        $balanceRequest->update([
            'status' => 'approved',
            'approved_balance_limit' => $request->approved_balance_limit,
            'admin_notes' => $request->admin_notes,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        // Update the branch's balance limit
        $balanceRequest->storeBranch()->update([
            'balance_limit' => $request->approved_balance_limit,
            'is_active' => true, // Activate the branch
        ]);

        return response()->json([
            'message' => 'Balance request approved successfully',
            'data' => $balanceRequest,
        ]);
    }

    /**
     * Reject a balance request
     */
    public function reject(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'rejection_reason' => 'required|string',
            'admin_notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $balanceRequest = BranchBalanceRequest::findOrFail($id);

        $balanceRequest->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'admin_notes' => $request->admin_notes,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        return response()->json([
            'message' => 'Balance request rejected',
            'data' => $balanceRequest,
        ]);
    }
}
