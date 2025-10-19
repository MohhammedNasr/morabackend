<?php

namespace App\Services\Bank;

use App\Models\Bank;
use App\Http\Resources\BankResource;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Collection;

class BankService extends BaseService
{
    public function getAllBanks()
    {
        $banks =  Bank::all();
        try {
            return $this->successResponse(
                data: BankResource::collection($banks),
                message: __('api.banks_retrieved')
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    public function getBankById(int $id): BankResource
    {
        $bank = Bank::findOrFail($id);
        return new BankResource($bank);
    }

    // public function formatResponse(Collection $banks): array
    // {
    //     // return [
    //     //     'success' => true,
    //     //     'data' => BankResource::collection($banks),
    //     //     'message' => 'Banks retrieved successfully'
    //     // ];

    // }
}
