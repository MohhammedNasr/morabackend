<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_id',
        'supplier_id',
        'status',
        'total_amount',
        'products_count',
        'sub_total',
        'discount',
        'promotion_id',
        'is_modified',
        'previous_amount',
        'modification_notes',
        'rejection_reason',
        'reference_number',
        'representative_id',
        'rejection_id'
    ];

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($subOrder) {
            $subOrder->order->updateStatusBasedOnSubOrders();
        });

        static::creating(function ($subOrder) {
            if (empty($subOrder->reference_number)) {
                $subOrder->reference_number = $subOrder->generateReferenceNumber() ?? '1';
            }
        });
    }

    protected $casts = [
        'total_amount' => 'decimal:2',
        'products_count' => 'integer',
    ];

    protected $appends = ['categories_count'];

    public const STATUS_PENDING = 'pending';
    public const STATUS_ACCEPTED_BY_SUPPLIER = 'acceptedBySupplier';
    public const STATUS_REJECTED_BY_SUPPLIER = 'rejectedBySupplier';
    public const STATUS_ASSIGNED_TO_REP = 'assignToRep';
    public const STATUS_REJECTED_BY_REP = 'rejectedByRep';
    public const STATUS_ACCEPTED_BY_REP = 'acceptedByRep';
    public const STATUS_MODIFIED_BY_SUPPLIER = 'modifiedBySupplier';
    public const STATUS_MODIFIED_BY_REP = 'modifiedByRep';
    public const STATUS_OUT_FOR_DELIVERY = 'outForDelivery';
    public const STATUS_DELIVERED = 'delivered';

    public static array $statusValues = [
        self::STATUS_PENDING,
        self::STATUS_ACCEPTED_BY_SUPPLIER,
        self::STATUS_REJECTED_BY_SUPPLIER,
        self::STATUS_ASSIGNED_TO_REP,
        self::STATUS_REJECTED_BY_REP,
        self::STATUS_ACCEPTED_BY_REP,
        self::STATUS_MODIFIED_BY_SUPPLIER,
        self::STATUS_MODIFIED_BY_REP,
        self::STATUS_OUT_FOR_DELIVERY,
        self::STATUS_DELIVERED
    ];

    public function getCategoriesCountAttribute(): int
    {
        return $this->items()
            ->with('product.category')
            ->get()
            ->pluck('product.category.id')
            ->unique()
            ->count();
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'sub_order_id')
            ->where('supplier_id', $this->supplier_id);
    }

    public function timelines(): HasMany
    {
        return $this->hasMany(SubOrderTimeline::class);
    }

    public function representative(): BelongsTo
    {
        return $this->belongsTo(Representative::class);
    }

    private function generateReferenceNumber()
    {
        $subOrderCount = SubOrder::where('order_id', $this->order_id)->count();
        return $this->order->reference_number . ($subOrderCount + 1);
    }

    /**
     * Scope to filter suborders that are verified or require mora approval
     * and belong to non-pending orders
     */
    public function scopeVerifiedOrRequiresMoraApproval($query)
    {
        return $query->whereHas('order', function ($q) {
            $q->where('status', '!=', Order::STATUS_PENDING);
        });
    }
}
