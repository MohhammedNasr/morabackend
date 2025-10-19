<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderTimeline extends Model
{
    use HasFactory , SoftDeletes;
    protected $fillable = [
        'order_id',
        'event',
        'description',
        'created_by',
        'user_id',
        'created_at'
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
