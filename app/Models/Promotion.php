<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Promotion extends Model
{
    use HasFactory , SoftDeletes;

    protected $fillable = [
        'code',
        'description',
        'discount_type',
        'discount_value',
        'minimum_order_amount',
        'maximum_discount_amount',
        'start_date',
        'end_date',
        'usage_limit',
        'used_count',
        'status',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'minimum_order_amount' => 'decimal:2',
        'maximum_discount_amount' => 'decimal:2',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'status' => 'string',
        'usage_limit' => 'integer',
        'used_count' => 'integer',
    ];

    /**
     * Check if the promotion is valid for use
     *
     * @return bool
     */
    public function isValid(): bool
    {
        $now = Carbon::now();

        // Check if promotion is active
        if ($this->status !== 'active') {
            return false;
        }

        // Check if promotion has expired
        if ($now->isAfter($this->end_date) || $now->isBefore($this->start_date)) {
            return false;
        }

        // Check usage limit
        if ($this->usage_limit !== null && $this->used_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    /**
     * Calculate discount amount for given order total
     *
     * @param float $orderTotal
     * @return float|null Returns null if minimum order amount is not met
     */
    public function calculateDiscount(float $orderTotal): ?float
    {
        // Check minimum order amount
        if ($orderTotal < $this->minimum_order_amount) {
            return null;
        }

        $discountAmount = match($this->discount_type) {
            'percentage' => $orderTotal * ($this->discount_value / 100),
            'fixed' => $this->discount_value,
            default => 0,
        };

        // Apply maximum discount limit if set
        if ($this->maximum_discount_amount !== null) {
            $discountAmount = min($discountAmount, $this->maximum_discount_amount);
        }

        // Ensure discount doesn't exceed order total for fixed discounts
        if ($this->discount_type === 'fixed') {
            $discountAmount = min($discountAmount, $orderTotal);
        }

        return round($discountAmount, 2);
    }

    /**
     * Increment the used count of the promotion
     *
     * @return bool
     */
    public function incrementUsage(): bool
    {
        $this->used_count++;
        return $this->save();
    }
}
