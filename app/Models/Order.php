<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\OrderTimeline;
use App\Models\OrderPayment;
use App\Models\OrderItem;
use App\Models\Store;
use App\Models\StoreBranch;
use App\Models\SubOrder;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Promotion;
use App\Services\Promotion\PromotionService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->reference_number)) {
                //where('store_id', $order->store_id)
                $lastOrder = self::orderBy(DB::raw('CAST(reference_number AS UNSIGNED)'), 'desc')
                    ->first();

                $baseNumber = $lastOrder ? (int)$lastOrder->reference_number : 100000000;
                $order->reference_number = (string)max($baseNumber + 1, 100000001);
            }

            // Auto-verify order if store has it enabled
            if ($order->store->auto_verify_order) {
                $order->status = self::STATUS_VERIFIED;
                $order->verified_at = now();
            }
        });

        static::created(function ($order) {
            // Add timeline entry for auto-verified orders
            if ($order->store->auto_verify_order) {
                OrderTimeline::create([
                    'order_id' => $order->id,
                    'event' => 'Order Verified by the system',
                    'status' => self::STATUS_VERIFIED,
                    'notes' => 'Order automatically verified by the system',
                    'created_by' => request()->user()->id,
                    'user_id' => request()->user()->id,
                    'user_type' => request()->user()->role->slug
                ]);
            }
        });
    }

    protected $fillable = [
        'store_id',
        'store_branch_id',
        'reference_number',
        'status',
        'total_amount',
        'sub_total',
        'discount',
        'payment_due_date',
        'requires_mora_approval',
        'verification_code',
        'verified_at',
        'cancellation_reason',
        'notes',
        'main_name',
        'user_id',
        'user_type',
        'promo_code',
        'promotion_id',
        'previous_sub_total',
        'previous_total_amount',
        'previous_quantity',
        'is_modified',
        'modification_notes'
    ];

    protected $appends = ['categories_count', 'suppliers_count'];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'payment_due_date' => 'date',
        'requires_mora_approval' => 'boolean',
        'verified_at' => 'datetime',
        'is_modified' => 'boolean',
        'previous_sub_total' => 'decimal:2',
        'previous_total_amount' => 'decimal:2',
        'previous_quantity' => 'integer'
    ];

    public function promotion(): BelongsTo
    {
        return $this->belongsTo(Promotion::class);
    }

    public function applyPromoCode(string $promoCode, PromotionService $promotionService): array
    {
        $result = $promotionService->applyPromotion($promoCode, $this->total_amount);

        if (!isset($result['success']) || (isset($result['success']) && !$result['success'])) {
            return [
                'success' => false,
                'message' => $result['message'] ?? 'Invalid promotion code'
            ];
        }

        $this->promo_code = $promoCode;
        $this->promotion_id = $result['promotion']->id;
        $this->total_amount = $result['final_amount'];
        $this->save();

        return [
            'success' => true,
            'message' => 'Promotion applied successfully'
        ];
    }

    public const STATUS_PENDING = 'pending';
    public const STATUS_VERIFIED = 'verified';
    public const STATUS_UNDER_PROCESSING = 'under_processing';
    public const STATUS_CANCELED = 'canceled';
    public const STATUS_COMPLETED = 'completed';

    public static $verificationStatuses = [
        self::STATUS_PENDING,
        self::STATUS_VERIFIED
    ];

    public function needsVerification(): bool
    {
        return !$this->store->auto_verify_order && $this->status === self::STATUS_PENDING;
    }

    public function verify(): void
    {
        $this->update([
            'status' => self::STATUS_VERIFIED,
            'verified_at' => now()
        ]);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function storeBranch(): BelongsTo
    {
        return $this->belongsTo(StoreBranch::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function timeline(): HasMany
    {
        return $this->hasMany(OrderTimeline::class);
    }

    public function subOrders(): HasMany
    {
        return $this->hasMany(SubOrder::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(OrderPayment::class);
    }

    public function suppliers(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(
            Supplier::class,
            SubOrder::class,
            'order_id',
            'id',
            'id',
            'supplier_id'
        )->select('suppliers.id', 'suppliers.name', 'sub_orders.order_id');
    }

    public function getProductsCountAttribute(): int
    {
        return $this->items->sum('quantity');
    }

    public function getCategoriesCountAttribute(): int
    {
        return $this->items()
            ->with('product.category')
            ->get()
            ->unique('product.category_id')
            ->count();
    }

    public function getSuppliersCountAttribute(): int
    {
        return $this->suppliers()->count();
    }

    public function scopeUpcoming($query)
    {
        return $query->whereIn('status', [
            self::STATUS_PENDING,
            self::STATUS_VERIFIED,
            self::STATUS_UNDER_PROCESSING,
        ]);
    }

    public function scopeHistorical($query)
    {
        return $query->whereIn('status', [
            self::STATUS_CANCELED,
            self::STATUS_COMPLETED,
        ]);
    }

    public static function generateVerificationCode(): string
    {
        return str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);
    }

    public function isVerificationCodeValid(?string $code): bool
    {
        return $code && $this->verification_code === $code &&
            $this->created_at->gt(now()->subMinutes(30));
    }

    // public function getSimplifiedStatus(): string
    // {
    //    if($this->status == 'pending'){

    //    }
    // }

    public function sendVerificationCode(): void
    {
        Log::info("Order verification code sent: {$this->verification_code}");
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isVerified(): bool
    {
        return $this->status === self::STATUS_VERIFIED;
    }

    public function isUnderProcessing(): bool
    {
        return $this->status === self::STATUS_UNDER_PROCESSING;
    }

    public function isCanceled(): bool
    {
        return $this->status === self::STATUS_CANCELED;
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function recalculateTotals(): void
    {
        $subTotal = $this->items()->sum(DB::raw('quantity * unit_price'));
        $discount = $this->discount ?? 0;

        $this->update([
            'sub_total' => $subTotal,
            'total_amount' => $subTotal - $discount
        ]);
    }

    public function updateStatusBasedOnSubOrders(): void
    {
        // Get all suborders except rejectedBySupplier ones
        $activeSubOrders = $this->subOrders()
            ->where('status', '!=', SubOrder::STATUS_REJECTED_BY_SUPPLIER)
            ->get();

        // If all active suborders are delivered, mark order as completed
        if ($activeSubOrders->every(fn($so) => $so->status === SubOrder::STATUS_DELIVERED)) {
            $this->update(['status' => self::STATUS_COMPLETED]);
            return;
        }

        // If any active suborder is in processing status
        $processingStatuses = [
            SubOrder::STATUS_ACCEPTED_BY_SUPPLIER,
            SubOrder::STATUS_ASSIGNED_TO_REP,
            SubOrder::STATUS_ACCEPTED_BY_REP,
            SubOrder::STATUS_MODIFIED_BY_SUPPLIER,
            SubOrder::STATUS_MODIFIED_BY_REP,
            SubOrder::STATUS_OUT_FOR_DELIVERY
        ];

        if ($activeSubOrders->contains(fn($so) => in_array($so->status, $processingStatuses))) {
            $this->update(['status' => self::STATUS_UNDER_PROCESSING]);
        }
    }
}
