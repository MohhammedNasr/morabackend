<?php

namespace App\Services\Notification;

use App\Services\BaseService;
use App\Models\Notification;
use App\Models\User;
use App\Models\UserDeviceToken;
use App\Http\Resources\NotificationResource;
use Illuminate\Http\JsonResponse;

class NotificationService extends BaseService
{
    /**
     * Get paginated notifications for a user
     */
    public function getUserNotifications($authenticatable, array $data): JsonResponse
    {
        try {
            $perPage = $data['per_page'] ?? 15;

            $paginatedNotifications = $authenticatable->notifications()
                ->latest()
                ->paginate($perPage);

            return $this->successResponse(
                data: [
                    'notifications' => NotificationResource::collection($paginatedNotifications->items()),
                    'pagination' => [
                        'current_page' => $paginatedNotifications->currentPage(),
                        'per_page' => $paginatedNotifications->perPage(),
                        'total' => $paginatedNotifications->total(),
                        'last_page' => $paginatedNotifications->lastPage(),
                    ],
                ],
                message: 'Notifications retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Notification $notification): Notification
    {
        if (!$notification->read_at) {
            $notification->update(['read_at' => now()]);
        }
        return $notification;
    }

    /**
     * Mark all notifications as read for a user
     */
    public function markAllAsRead($authenticatable): void
    {
        $authenticatable->unreadNotifications()->update(['read_at' => now()]);
    }

    /**
     * Create a new notification
     */
    public function createNotification($notifiable, array $data): Notification
    {
        $data['notifiable_id'] = $notifiable->id;
        $data['user_id'] = $notifiable->id;
        $data['notifiable_type'] = get_class($notifiable);
        $data['user_type'] = get_class($notifiable);

        return Notification::create($data);
    }

    /**
     * Delete a notification
     */
    public function deleteNotification(Notification $notification): bool
    {
        return $notification->delete();
    }

    /**
     * Register a device token for push notifications
     */
    public function registerDeviceToken($authenticatable, string $deviceToken, string $deviceType, string $userType): JsonResponse
    {
        try {

            // Delete any existing tokens for this device
            UserDeviceToken::where('user_id', $authenticatable->id)->delete();

            // Create new token record
            $result =  $authenticatable->deviceTokens()->create([
                // 'device_token' => $deviceToken,
                'user_type' => $userType,
                'platform' => $deviceType,
                'token' => $deviceToken
            ]);

            return $this->successResponse(
                message: 'Device token registered successfully'
            );
        } catch (\Exception $e) {
            dd($e->getMessage());
            return $this->handleException($e);
        }
    }
}
