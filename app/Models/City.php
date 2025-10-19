<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use HasFactory , SoftDeletes;

    protected $fillable = [
        'name_ar',
        'name_en',
        'code',
    ];

    public function areas(): HasMany
    {
        return $this->hasMany(Area::class);
    }
}
