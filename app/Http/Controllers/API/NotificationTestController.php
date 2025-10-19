<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\Notification\FirebaseNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NotificationTestController extends Controller
{
    protected $notificationService;

    public function __construct(FirebaseNotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function sendTestNotification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
            'title' => 'required|string|max:100',
            'message' => 'required|string|max:255',
            'user_type' => 'required|string|in:user,supplier,representative'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
          $result=  $this->notificationService->sendToUser(
                $request->user_id,
                $request->user_type,
                $request->title,
                $request->message
            );
          
        

            
            return response()->json([
                'success' => true,
                'message' => 'Test notification sent successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send notification',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
