<?php

namespace App\Jobs;

use Illuminate\Contracts\Auth\Authenticatable;
use App\Services\Notification\FirebaseNotificationService;
use App\Services\Notification\NotificationService;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendOrderStatusNotification implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private int $userId,
        private string $userType,
        private string $title,
        private string $body,
        private array $data
    ) {}

    public function handle(FirebaseNotificationService $firebaseService, NotificationService $notificationService)
    {

        // Combine title/body with data for Firebase
        $firebaseData = array_merge($this->data, [
            'title' => $this->title,
            'body' => $this->body
        ]);

        $firebaseService->sendToUser(
            $this->userId,
            $this->userType,
            $this->title, // Empty title
            $this->body, // Empty body
            $firebaseData
        );


        // Get the notifiable model based on user type
        $modelClass = match($this->userType) {
            'user' => \App\Models\User::class,
            'supplier' => \App\Models\Supplier::class,
            'representative' => \App\Models\Representative::class,
            default => throw new \InvalidArgumentException("Invalid user type: {$this->userType}")
        };

        $notifiable = $modelClass::findOrFail($this->userId);

        $notificationService->createNotification($notifiable, [
            'title' => $this->title,
            'body' => $this->body,
            'data' => $this->data,
            'read_at' => null
        ]);
    }
}
