<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name_en',
        'name_ar',
        'slug',
        'description_en',
        'description_ar',
        'image',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function getName(string $locale = null): string
    {
        $locale = $locale ?: app()->getLocale();
        return $locale === 'ar' ? $this->name_ar : $this->name_en;
    }

    public function getDescription(string $locale = null): ?string
    {
        $locale = $locale ?: app()->getLocale();
        return $locale === 'ar' ? $this->description_ar : $this->description_en;
    }

    public function scopeIsActive($query)
    {
        return $query->where('status', 'active');
    }
}
