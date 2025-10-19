<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RejectionReason extends Model
{
    use HasFactory;

    protected $fillable = [
        'reason_ar',
        'reason_en',
        'type' // 'supplier' or 'representative'
    ];

    public function rejectable()
    {
        return $this->morphTo();
    }

    public static function getForRole(string $role)
    {
        return self::where('type', $role)->get();
    }
}
