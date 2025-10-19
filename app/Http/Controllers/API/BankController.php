<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\Bank\BankService;
use Illuminate\Http\JsonResponse;

class BankController extends Controller
{
    public function __construct(
        private BankService $bankService
    ) {}

    /**
     * Display a listing of KSA banks.
     */
    public function index(): JsonResponse
    {
        return $this->bankService->getAllBanks();
    }
}
