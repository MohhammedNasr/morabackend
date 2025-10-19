<?php

namespace App\Services\Promotion;

use App\Models\Promotion;
use App\Services\BaseService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PromotionService extends BaseService
{
    /**
     * Validate a promotion code
     *
     * @param string $code
     * @param float $orderTotal
     * @return array
     */
    public function validatePromotion(string $code, float $orderTotal): array
    {
        try {
            $promotion = Promotion::where('code', $code)->firstOrFail();

            if (!$promotion->isValid()) {
                return [
                    'valid' => false,
                    'message' => __('api.promotion_expired'),
                ];
            }

            if ($orderTotal < $promotion->minimum_order_amount) {
                return [
                    'valid' => false,
                    'message' => __('api.order_amount_requirement'),
                    'minimum_amount' => $promotion->minimum_order_amount,
                ];
            }

            $discountAmount = $promotion->calculateDiscount($orderTotal);

            return [
                'valid' => true,
                'promotion' => $promotion,
                'discount_amount' => $discountAmount,
                'final_amount' => $orderTotal - $discountAmount,
            ];
        } catch (ModelNotFoundException $e) {
            return [
                'valid' => false,
                'message' => __('api.invalid_promotion_code'),
            ];
        }
    }

    /**
     * Apply promotion to order
     *
     * @param string $code
     * @param float $orderTotal
     * @return array
     */
    public function applyPromotion(string $code, float $orderTotal): array
    {
        $validation = $this->validatePromotion($code, $orderTotal);

        if (!$validation['valid']) {
            return $validation;
        }

        $promotion = $validation['promotion'];
        $promotion->incrementUsage();

        return [
            'success' => true,
            'discount_amount' => $validation['discount_amount'],
            'final_amount' => $validation['final_amount'],
            'promotion' => $promotion,
        ];
    }
}
