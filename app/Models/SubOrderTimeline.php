<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubOrderTimeline extends Model
{
    use HasFactory;

    protected $fillable = [
        'sub_order_id',
        'status',
        'notes',
        'created_by',
        'user_id'
    ];

    public function subOrder(): BelongsTo
    {
        return $this->belongsTo(SubOrder::class);
    }
}
