<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Services\Supplier\ForgetPasswordService;
use Illuminate\Http\Request;

class SupplierForgetPasswordController extends Controller
{
    protected $forgetPasswordService;

    public function __construct(ForgetPasswordService $forgetPasswordService)
    {
        $this->forgetPasswordService = $forgetPasswordService;
    }

    public function sendOtp(Request $request)
    {
        $request->validate(['phone' => 'required|string']);
        return $this->forgetPasswordService->sendOtp($request->phone);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'otp' => 'required|numeric'
        ]);
        return $this->forgetPasswordService->verifyOtp($request->phone, $request->otp);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'reset_token' => 'required|string',
            'password' => 'required|string|min:8|confirmed'
        ]);
        return $this->forgetPasswordService->resetPassword($request->reset_token, $request->password);
    }
}
