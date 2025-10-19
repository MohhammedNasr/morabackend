<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\City;
use App\Models\Area;

class StoreBranch extends Model
{
    use HasFactory , SoftDeletes;

    protected $fillable = [
        'store_id',
        'name',
        'street_name',
        'phone',
        'latitude',
        'longitude',
        'is_main',
        'is_active',
        'building_number',
        'floor_number',
        'city_id',
        'area_id',
        'notes',
        'main_name',
        'balance_limit'
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
