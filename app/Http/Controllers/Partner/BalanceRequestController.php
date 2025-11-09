<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\BranchBalanceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BalanceRequestController extends Controller
{
    /**
     * Get all balance requests pending partner review
     */
    public function index()
    {
        try {
            $requests = BranchBalanceRequest::with([
                'storeBranch.store', 
                'employeeReviewer', 
                'managerReviewer'
            ])
                ->where('status', 'manager_review')
                ->orderBy('manager_reviewed_at', 'desc')
                ->get()
                ->map(function($req) {
                    // Debug logging
                    \Log::info('Partner request details', [
                        'id' => $req->id,
                        'store_branch_id' => $req->store_branch_id,
                        'store_id' => $req->store_id,
                        'has_branch' => $req->storeBranch ? 'yes' : 'no',
                        'branch_name' => $req->storeBranch?->name,
                        'has_store' => $req->storeBranch?->store ? 'yes' : 'no',
                        'store_name' => $req->storeBranch?->store?->name,
                    ]);
                    
                    return [
                        'id' => $req->id,
                        'request_number' => $req->request_number,
                        'branch_name' => $req->storeBranch?->name ?? 'N/A',
                        'store_name' => $req->storeBranch?->store?->name ?? 'N/A',
                        'requested_balance_limit' => (float) $req->requested_balance_limit,
                        'status' => $req->status,
                        'employee_comment' => $req->employee_comment,
                        'employee_reviewed_by' => $req->employeeReviewer?->name,
                        'employee_reviewed_at' => $req->employee_reviewed_at?->format('Y-m-d H:i:s'),
                        'manager_comment' => $req->manager_comment,
                        'manager_reviewed_by' => $req->managerReviewer?->name,
                        'manager_reviewed_at' => $req->manager_reviewed_at?->format('Y-m-d H:i:s'),
                        'created_at' => $req->created_at?->format('Y-m-d H:i:s'),
                    ];
                });

            return response()->json($requests);
        } catch (\Exception $e) {
            \Log::error('Partner balance requests error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to fetch partner review requests',
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
            $request = BranchBalanceRequest::with([
                'storeBranch.store', 
                'employeeReviewer', 
                'managerReviewer',
                'partnerReviewer'
            ])
                ->findOrFail($id);

            return response()->json([
                'id' => $request->id,
                'request_number' => $request->request_number,
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
                // Employee review
                'employee_status' => $request->employee_status,
                'employee_comment' => $request->employee_comment,
                'employee_reviewed_by' => $request->employeeReviewer?->name,
                'employee_reviewed_at' => $request->employee_reviewed_at?->format('Y-m-d H:i:s'),
                // Manager review
                'manager_status' => $request->manager_status,
                'manager_comment' => $request->manager_comment,
                'manager_reviewed_by' => $request->managerReviewer?->name,
                'manager_reviewed_at' => $request->manager_reviewed_at?->format('Y-m-d H:i:s'),
                // Partner review
                'partner_status' => $request->partner_status,
                'partner_comment' => $request->partner_comment,
                'partner_reviewed_by' => $request->partnerReviewer?->name,
                'partner_reviewed_at' => $request->partner_reviewed_at?->format('Y-m-d H:i:s'),
                'approved_balance_limit' => $request->approved_balance_limit ? (float) $request->approved_balance_limit : null,
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
     * Partner approve a balance request (final approval)
     */
    public function approve(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'approved_balance_limit' => 'required|numeric|min:0',
            'comment' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $balanceRequest = BranchBalanceRequest::where('status', 'manager_review')->findOrFail($id);

        $balanceRequest->update([
            'status' => 'approved',
            'partner_status' => 'approved',
            'partner_comment' => $request->comment,
            'partner_reviewed_by' => auth()->id(),
            'partner_reviewed_at' => now(),
            'approved_balance_limit' => $request->approved_balance_limit,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        // Update the branch's balance limit
        $balanceRequest->storeBranch()->update([
            'balance_limit' => $request->approved_balance_limit,
            'is_active' => true,
        ]);

        return response()->json([
            'message' => 'Balance request approved successfully by partner. Branch balance limit updated.',
            'data' => $balanceRequest,
        ]);
    }

    /**
     * Partner reject a balance request (final rejection)
     */
    public function reject(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'comment' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $balanceRequest = BranchBalanceRequest::where('status', 'manager_review')->findOrFail($id);

        $balanceRequest->update([
            'status' => 'rejected',
            'partner_status' => 'rejected',
            'partner_comment' => $request->comment,
            'partner_reviewed_by' => auth()->id(),
            'partner_reviewed_at' => now(),
            'rejection_reason' => $request->comment ?? 'Rejected by partner',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        return response()->json([
            'message' => 'Balance request rejected by partner',
            'data' => $balanceRequest,
        ]);
    }
}
