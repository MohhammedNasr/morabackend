<?php

namespace App\Services\Store;

use App\Services\BaseService;
use Illuminate\Http\JsonResponse;

class StoreTypeService extends BaseService
{
    /**
     * Get available store types
     */
    public function getStoreTypes(): JsonResponse
    {
        try {
            $types = [
                [
                    'id' => 'hypermarket',
                    'name' => (app()->getLocale() === 'en') ? 'Hypermarket' : 'هايبر ماركت',
                    'credit_balance' => 10000,
                ],
                [
                    'id' => 'supermarket',
                    'name' => (app()->getLocale() == 'en') ? 'SuperMarket' : 'سوبر ماركت',
                    'credit_balance' => 5000,
                ],
                [
                    'id' => 'restaurant',
                    'name' => (app()->getLocale() === 'en') ? 'Restaurant' : 'مطعم',
                    'credit_balance' => 2000,
                ],
            ];

            return $this->successResponse(
                data: ['types' => $types],
                message: 'Store types retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }
}
