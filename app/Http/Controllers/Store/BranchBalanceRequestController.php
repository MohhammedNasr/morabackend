<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\BranchBalanceRequest;
use App\Models\StoreBranch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BranchBalanceRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum,web');
        $this->middleware(function ($request, $next) {
            // Try sanctum first (API), then web
            $user = auth()->guard('sanctum')->user() ?? auth()->guard('web')->user();
            if (!$user || !$user->store) {
                abort(403, 'Unauthorized action.');
            }
            return $next($request);
        });
    }

    /**
     * Get all balance requests for the store
     */
    public function index(Request $request)
    {
        $user = auth()->guard('sanctum')->user() ?? auth()->guard('web')->user();
        $storeId = $user->store->id;
        
        $requests = BranchBalanceRequest::with(['storeBranch', 'reviewer'])
            ->where('store_id', $storeId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($req) {
                return [
                    'id' => $req->id,
                    'branch_name' => $req->storeBranch->name,
                    'requested_balance_limit' => (float) $req->requested_balance_limit,
                    'status' => $req->status,
                    'approved_balance_limit' => $req->approved_balance_limit ? (float) $req->approved_balance_limit : null,
                    'rejection_reason' => $req->rejection_reason,
                    'created_at' => $req->created_at?->format('Y-m-d H:i:s'),
                    'reviewed_at' => $req->reviewed_at?->format('Y-m-d H:i:s'),
                ];
            });

        return response()->json($requests);
    }

    /**
     * Get a single balance request
     */
    public function show($id)
    {
        $user = auth()->guard('sanctum')->user() ?? auth()->guard('web')->user();
        $storeId = $user->store->id;
        
        $request = BranchBalanceRequest::with(['storeBranch', 'reviewer'])
            ->where('store_id', $storeId)
            ->findOrFail($id);

        return response()->json([
            'id' => $request->id,
            'branch' => [
                'id' => $request->storeBranch->id,
                'name' => $request->storeBranch->name,
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
    }

    /**
     * Create a new balance request
     */
    public function store(Request $request)
    {
        $user = auth()->guard('sanctum')->user() ?? auth()->guard('web')->user();
        $storeId = $user->store->id;

        $validator = Validator::make($request->all(), [
            'store_branch_id' => 'required|exists:store_branches,id',
            'requested_balance_limit' => 'required|numeric|min:0',
            'business_type' => 'nullable|string|max:255',
            'years_in_business' => 'nullable|integer|min:0',
            'average_monthly_revenue' => 'nullable|numeric|min:0',
            'number_of_employees' => 'nullable|integer|min:0',
            'business_description' => 'nullable|string',
            'tax_registration_number' => 'nullable|string|max:255',
            'commercial_registration_number' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'iban' => 'nullable|string|max:255',
            'contact_person_name' => 'nullable|string|max:255',
            'contact_person_phone' => 'nullable|string|max:255',
            'contact_person_email' => 'nullable|email|max:255',
            'contact_person_position' => 'nullable|string|max:255',
            'documents' => 'nullable|array',
            'documents.*' => 'nullable|file|max:10240', // 10MB max per file
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Verify branch belongs to store
        $branch = StoreBranch::where('id', $request->store_branch_id)
            ->where('store_id', $storeId)
            ->firstOrFail();

        // Check if there's already a pending request for this branch
        $existingRequest = BranchBalanceRequest::where('store_branch_id', $branch->id)
            ->whereIn('status', ['pending', 'under_review'])
            ->first();

        if ($existingRequest) {
            return response()->json([
                'message' => 'There is already a pending request for this branch.'
            ], 422);
        }

        // Handle document uploads
        $documentPaths = [];
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $document) {
                $path = $document->store('balance_requests', 'public');
                $documentPaths[] = $path;
            }
        }

        // Create the balance request
        $balanceRequest = BranchBalanceRequest::create([
            'store_branch_id' => $branch->id,
            'store_id' => $storeId,
            'requested_balance_limit' => $request->requested_balance_limit,
            'business_type' => $request->business_type,
            'years_in_business' => $request->years_in_business,
            'average_monthly_revenue' => $request->average_monthly_revenue,
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
            'documents' => $documentPaths,
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Balance request submitted successfully',
            'data' => [
                'id' => $balanceRequest->id,
                'status' => $balanceRequest->status,
            ]
        ], 201);
    }

    /**
     * Update a balance request (only allowed if status is pending)
     */
    public function update(Request $request, $id)
    {
        $user = auth()->guard('sanctum')->user() ?? auth()->guard('web')->user();
        $storeId = $user->store->id;

        $balanceRequest = BranchBalanceRequest::where('store_id', $storeId)
            ->where('status', 'pending')
            ->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'requested_balance_limit' => 'sometimes|numeric|min:0',
            'business_type' => 'nullable|string|max:255',
            'years_in_business' => 'nullable|integer|min:0',
            'average_monthly_revenue' => 'nullable|numeric|min:0',
            'number_of_employees' => 'nullable|integer|min:0',
            'business_description' => 'nullable|string',
            'tax_registration_number' => 'nullable|string|max:255',
            'commercial_registration_number' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'iban' => 'nullable|string|max:255',
            'contact_person_name' => 'nullable|string|max:255',
            'contact_person_phone' => 'nullable|string|max:255',
            'contact_person_email' => 'nullable|email|max:255',
            'contact_person_position' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $balanceRequest->update($request->only([
            'requested_balance_limit',
            'business_type',
            'years_in_business',
            'average_monthly_revenue',
            'number_of_employees',
            'business_description',
            'tax_registration_number',
            'commercial_registration_number',
            'bank_account_number',
            'bank_name',
            'iban',
            'contact_person_name',
            'contact_person_phone',
            'contact_person_email',
            'contact_person_position',
        ]));

        return response()->json([
            'message' => 'Balance request updated successfully',
            'data' => $balanceRequest,
        ]);
    }

    /**
     * Delete a balance request (only allowed if status is pending)
     */
    public function destroy($id)
    {
        $user = auth()->guard('sanctum')->user() ?? auth()->guard('web')->user();
        $storeId = $user->store->id;

        $balanceRequest = BranchBalanceRequest::where('store_id', $storeId)
            ->where('status', 'pending')
            ->findOrFail($id);

        // Delete associated documents
        if ($balanceRequest->documents) {
            foreach ($balanceRequest->documents as $document) {
                Storage::disk('public')->delete($document);
            }
        }

        $balanceRequest->delete();

        return response()->json(['message' => 'Balance request deleted successfully']);
    }
}
