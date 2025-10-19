<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\RejectionReason;
use Illuminate\Http\Request;

class RejectionReasonController extends Controller
{
    public function index(Request $request)
    {
        $role = null;
        if ($request->user()) {
            $role = $request->user()->role->slug;
        }


        $query = RejectionReason::query();

        if ($role && in_array($role, ['supplier', 'representative'])) {
            $query->where('type', $role);
        }

        $reasons = $query->select('id', 'reason_' . app()->getLocale() . ' as name')->get();

        return response()->json([
            'success' => true,
            'data' => $reasons
        ]);
    }
}
