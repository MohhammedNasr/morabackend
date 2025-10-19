<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Setting extends Model
{
    use HasFactory , SoftDeletes;
    protected $fillable = [
        'name',
        'logo',
        'primary_color',
        'secondary_color',
        'description',
        'facebook',
        'instagram',
        'twitter',
        'youtube',
        'whatsapp',
        'emails',
        'phone',
        'address',
        'latitude',
        'longitude',
        'currency_name_en',
        'currency_name_ar',
        'currency_symbol_en',
        'currency_symbol_ar'
    ];
}
