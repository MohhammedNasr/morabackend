<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\Auth\WebAuthService;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    protected WebAuthService $webAuthService;

    public function __construct(WebAuthService $webAuthService)
    {
        $this->webAuthService = $webAuthService;
    }

    public function verify(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'code' => 'required|string|size:4',
        ]);

        return $this->webAuthService->verifyPhone($request->all());
    }

    public function resend(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|exists:users,phone',
        ]);

        return $this->webAuthService->resendVerificationCode($request->all());
    }
}
