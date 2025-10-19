<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDeviceToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_type',
        'token',
        'platform'
    ];

    protected $casts = [
        'platform' => 'string'
    ];

    public function setPlatformAttribute($value)
    {
        $value = strtolower($value);
        
        // Auto-detect platform if not specified
        if (empty($value)) {
            $value = $this->detectPlatformFromToken();
        }
        
        $this->attributes['platform'] = in_array($value, ['ios', 'android']) ? $value : 'unknown';
    }

    protected function detectPlatformFromToken()
    {
        if (empty($this->token)) {
            return 'unknown';
        }

        // iOS tokens are typically 64 chars of lowercase letters/numbers
        if (preg_match('/^[a-z0-9]{64}$/', $this->token)) {
            return 'ios';
        }

        // Android tokens are longer and contain various characters
        return 'android';
    }

    /**
     * Get the owning user model.
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo(null, 'user_type', 'user_id');
    }
}
