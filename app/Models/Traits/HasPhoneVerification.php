<?php

namespace App\Models\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

trait HasPhoneVerification
{
    /**
     * Determine if the user has verified their phone number.
     *
     * @return bool
     */
    public function hasVerifiedPhone()
    {
        // Skip verification check for admin users
        if ($this->isAdmin()) {
            return true;
        }
        return ! is_null($this->phone_verified_at);
    }

    /**
     * Mark the given user's phone as verified.
     *
     * @return bool
     */
    public function markPhoneAsVerified()
    {
        return $this->forceFill([
            'phone_verified_at' => $this->freshTimestamp(),
            'is_verified' => 1,
            // 'phone_verification_code' => null,
            // 'phone_verification_code_expires_at' => null,
        ])->save();
    }

    public function markStoreAsVerified()
    {
        foreach ($this->stores as $store) {
            $store->update([
                'is_verified' => 1
            ]);
        }
    }

    /**
     * Generate a new phone verification code.
     *
     * @return string
     */
    public function generatePhoneVerificationCode()
    {
        // Generate a 4-digit verification code
        $code = str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);

        // Save the code and set expiration (30 minutes from now)
        $this->forceFill([
            'phone_verification_code' => $code,
            'phone_verification_code_expires_at' => now()->addMinutes(30),
        ])->save();

        // TODO: Implement SMS sending here
        // For now, just log the code
        Log::info("Phone verification code for {$this->phone}: {$code}");

        return $code;
    }

    /**
     * Verify the given code.
     *
     * @param string $code
     * @return bool
     */
    public function verifyPhoneCode($code)
    {
        if ($this->phone_verification_code !== $code) {
            return false;
        }

        if (now()->gt($this->phone_verification_code_expires_at)) {
            return false;
        }

        $this->markPhoneAsVerified();
        return true;
    }
}
