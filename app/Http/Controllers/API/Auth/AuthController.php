<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Resources\SupplierResource;
use App\Http\Resources\RepresentativeResource;
use App\Http\Resources\StoreResource;

class AuthController extends Controller
{
    public function me(Request $request)
    {
        $user = $request->user();
        
        if ($user instanceof \App\Models\Supplier) {
            return response()->json([
                'user' => new UserResource($user),
                'supplier' => new SupplierResource($user)
            ]);
        } elseif ($user instanceof \App\Models\Representative) {
            return response()->json([
                'user' => new UserResource($user),
                'representative' => new RepresentativeResource($user),
                'supplier' => $user->supplier ? new SupplierResource($user->supplier) : null
            ]);
        } else {
            // For store owners
            $user->load('stores.branches');
            return response()->json([
                'user' => new UserResource($user),
                'store' => new StoreResource($user->stores->first())
            ]);
        }
    }
}
