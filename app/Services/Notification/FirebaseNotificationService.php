<?php

namespace App\Services\Notification;

use Illuminate\Contracts\Auth\Authenticatable;
use App\Models\DeviceToken;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;
use Illuminate\Support\Facades\Log;

class FirebaseNotificationService
{
    protected $messaging;

    public function __construct()
    {
        $this->messaging = app('firebase.messaging');
    }

    /**
     * @param \Illuminate\Contracts\Auth\Authenticatable|\Illuminate\Database\Eloquent\Collection $user
     */
    public function sendToUser(int $userId, string $userType, string $title, string $body, array $data = [])
    {
        $model = match ($userType) {
            'user' => \App\Models\User::class,
            'supplier' => \App\Models\Supplier::class,
            'representative' => \App\Models\Representative::class,
            default => null
        };

        if (!$model) {
            return;
        }

        $user = $model::find($userId);
        if (!$user || !method_exists($user, 'deviceTokens')) {
            return;
        }

        $deviceTokens = $user->deviceTokens()
            ->whereNotNull('token')
            ->where('token', '!=', '')
            ->latest() // Get most recent tokens first
            ->get(['token', 'platform']);

        if ($deviceTokens->isEmpty()) {
            Log::warning("No valid device tokens found for user {$userId}");
            return;
        }

        try {

            // Group tokens by platform
            $androidTokens = [];
            $iosTokens = [];

            foreach ($deviceTokens as $token) {
                $platform = strtolower($token->platform);
                Log::info("Processing device token", [
                    'token' => $token->token,
                    'platform' => $platform
                ]);

                if ($platform === 'android') {
                    $androidTokens[] = $token->token;
                } elseif ($platform === 'ios') {
                    $iosTokens[] = $token->token;
                }
                // } else {
                //     // Try to detect iOS tokens by format (starts with lowercase letters and numbers)
                //     if (preg_match('/^[a-z0-9]{32,}$/', $token->token)) {
                //         Log::info("Assuming iOS device based on token format", [
                //             'token' => $token->token
                //         ]);
                //         $iosTokens[] = $token->token;
                //     } else {
                //         Log::warning("Unknown platform for device token - defaulting to Android", [
                //             'token' => $token->token,
                //             'platform' => $platform
                //         ]);
                //         $androidTokens[] = $token->token;
                //     }
                // }
            }

            // Send to Android devices (data-only)
            if (!empty($androidTokens)) {
                $androidMessage = CloudMessage::new()
                    ->withData(array_merge($data, [
                        'title' => $title,
                        'body' => $body,
                        'user_type' => $userType

                        // ($userType === 'user' ? 'order_id' : 'sub_order_id') => $data['order_id'] ?? null
                    ]));

                $this->messaging->sendMulticast($androidMessage, $androidTokens);
            }

            // Send to iOS devices (notification + data)
            if (!empty($iosTokens)) {
                // Ensure we have non-empty title/body for notification
                $notificationTitle = empty($title) ? ($data['title'] ?? '') : $title;
                $notificationBody = empty($body) ? ($data['body'] ?? '') : $body;

                $iosMessage = CloudMessage::new()
                    ->withNotification(FirebaseNotification::create($notificationTitle, $notificationBody))
                    ->withData(array_merge($data, [
                        'user_type' => $userType,
                        ($userType === 'user' ? 'order_id' : 'sub_order_id') => $data['order_id'] ?? null
                    ]))
                    ->withApnsConfig([
                        'payload' => [
                            'aps' => [
                                'alert' => [
                                    'title' => $title,
                                    'body' => $body,
                                ],
                                'sound' => 'default',
                                'content-available' => 1,
                                'mutable-content' => 1
                            ],
                        ],
                        'headers' => [
                            'apns-priority' => '10'
                        ]
                    ]);

                $result =  $this->messaging->sendMulticast($iosMessage, $iosTokens);

                Log::error(var_dump($result));
            }
        } catch (\Throwable $e) {
            Log::error("Failed to send notification to user {$userId}: " . $e->getMessage());
        }
    }

    /**
     * @param array $tokens Array of device tokens or array of ['token' => string, 'platform' => string]
     */
    public function sendToTokens(array $tokens, string $title, string $body, array $data = [])
    {
        // Normalize tokens array
        $normalizedTokens = [];
        foreach ($tokens as $token) {
            if (is_array($token)) {
                if (!empty($token['token'])) {
                    $normalizedTokens[] = [
                        'token' => $token['token'],
                        'platform' => strtolower($token['platform'] ?? 'unknown')
                    ];
                }
            } elseif (is_string($token)) {
                $normalizedTokens[] = [
                    'token' => $token,
                    'platform' => 'unknown'
                ];
            }
        }

        if (empty($normalizedTokens)) {
            Log::warning("No valid device tokens provided for sendToTokens");
            return;
        }

        // Group tokens by platform
        $androidTokens = [];
        $iosTokens = [];
        $unknownTokens = [];

        foreach ($normalizedTokens as $token) {
            if ($token['platform'] === 'android') {
                $androidTokens[] = $token['token'];
            } elseif ($token['platform'] === 'ios') {
                $iosTokens[] = $token['token'];
            } else {
                $unknownTokens[] = $token['token'];
            }
        }

        try {
            // Send to Android devices (data-only)
            if (!empty($androidTokens)) {
                $androidMessage = CloudMessage::new()
                    ->withData(array_merge($data, [
                        'title' => $title,
                        'body' => $body
                    ]));
                $this->messaging->sendMulticast($androidMessage, $androidTokens);
            }

            // Send to iOS devices (notification + data)
            if (!empty($iosTokens)) {
                $iosMessage = CloudMessage::new()
                    ->withNotification(FirebaseNotification::create($title, $body))
                    ->withData($data)
                    ->withApnsConfig([
                        'payload' => [
                            'aps' => [
                                'alert' => [
                                    'title' => $title,
                                    'body' => $body,
                                ],
                                'sound' => 'default',
                                'content-available' => 1,
                                'mutable-content' => 1
                            ],
                        ],
                        'headers' => [
                            'apns-priority' => '10'
                        ]
                    ]);
                $this->messaging->sendMulticast($iosMessage, $iosTokens);
            }

            // For unknown platforms, send both formats
            if (!empty($unknownTokens)) {
                $dataMessage = CloudMessage::new()
                    ->withData(array_merge($data, [
                        'title' => $title,
                        'body' => $body
                    ]));
                $this->messaging->sendMulticast($dataMessage, $unknownTokens);

                $notificationMessage = CloudMessage::new()
                    ->withNotification(FirebaseNotification::create($title, $body))
                    ->withData($data);
                $this->messaging->sendMulticast($notificationMessage, $unknownTokens);
            }
        } catch (\Throwable $e) {
            Log::error("Failed to send notification to tokens: " . $e->getMessage());
        }
    }
}
