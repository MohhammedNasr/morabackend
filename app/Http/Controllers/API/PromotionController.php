<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Promotion\ApplyPromotionRequest;
use App\Http\Requests\Promotion\ValidatePromotionRequest;
use App\Services\Promotion\PromotionService;
use App\Traits\ApiResponse;

class PromotionController extends Controller
{
    use ApiResponse;

    protected $promotionService;

    public function __construct(PromotionService $promotionService)
    {
        $this->promotionService = $promotionService;
    }

    /**
     * Validate a promotion code
     *
     * @param ValidatePromotionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function validatePromotion(ValidatePromotionRequest $request)
    {
        $result = $this->promotionService->validatePromotion(
            $request->code,
            $request->order_total
        );

        if (!$result['valid']) {
            return $this->errorResponse($result['message']);
        }

        return $this->successResponse($result, __('Promotion code is valid'));
    }

    /**
     * Apply a promotion code to order
     *
     * @param ApplyPromotionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function applyPromotion(ApplyPromotionRequest $request)
    {
        $result = $this->promotionService->applyPromotion(
            $request->code,
            $request->order_total
        );

        if (!isset($result['success'])) {
            return $this->errorResponse($result['message']);
        }

        return $this->successResponse($result, __('Promotion applied successfully'),);
    }
}
