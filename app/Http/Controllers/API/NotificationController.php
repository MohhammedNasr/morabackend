<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Notification;
use App\Models\UserDeviceToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\Notification\NotificationService;
use App\Http\Resources\NotificationResource;

class NotificationController extends Controller
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        
        return $this->notificationService->getUserNotifications($user, [
            'per_page' => $request->per_page ?? 15
        ]);
    }

    public function registerDeviceToken(Request $request)
    {
        $request->validate([
            'device_token' => 'required|string',
            'platform' => 'required|string|in:android,ios'
        ]);

        /** @var User $user */
        $user = Auth::user();
        
        // Delete any existing tokens for this device
        UserDeviceToken::where('device_token', $request->device_token)->delete();

        // Create new token
        $user->deviceTokens()->create([
            'device_token' => $request->device_token,
            'platform' => $request->platform
        ]);

        return response()->json([
            'message' => 'Device token registered successfully'
        ]);
    }

    public function sendPushNotification(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'body' => 'required|string',
            'data' => 'nullable|array'
        ]);

        /** @var User $user */
        $user = Auth::user();
        $tokens = $user->deviceTokens()->pluck('device_token')->toArray();

        if (empty($tokens)) {
            return response()->json([
                'message' => 'No device tokens found for this user'
            ], 400);
        }

        $notification = [
            'title' => $request->title,
            'body' => $request->body,
        ];

        $payload = [
            'registration_ids' => $tokens,
            'notification' => $notification,
            'data' => $request->data ?? [],
        ];

        $headers = [
            'Authorization: key=' . config('services.fcm.server_key'),
            'Content-Type: application/json'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, config('services.fcm.url'));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

        $result = curl_exec($ch);
        curl_close($ch);

        return response()->json([
            'message' => 'Notification sent successfully',
            'result' => json_decode($result)
        ]);
    }

    public function show($id)
    {
        /** @var User $user */
        $user = Auth::user();
        
        $notification = $user->notifications()
            ->where('id', $id)
            ->firstOrFail();
            
        // Mark notification as read
        $this->notificationService->markAsRead($notification);

        return response()->json([
            'data' => new NotificationResource($notification)
        ]);
    }

    public function destroy($id)
    {
        /** @var User $user */
        $user = Auth::user();
        
        $notification = $user->notifications()
            ->where('id', $id)
            ->firstOrFail();

        $notification->delete(); // This will now perform a soft delete

        return response()->json([
            'message' => 'Notification deleted successfully'
        ]);
    }
}
