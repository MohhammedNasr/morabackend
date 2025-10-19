<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        $notifications = [];
        $now = now();

        foreach ($users as $user) {
            for ($i = 1; $i <= 5; $i++) {
                $status = ['Pending', 'Processing', 'Shipped', 'Delivered'][rand(0, 3)];
                $notifications[] = [
                    'user_id' => $user->id,
                    'title' => 'Order Update',
                    'body' => 'Your order #' . rand(1000, 9999) . ' status has been updated',
                    'data' => json_encode([
                        'order_id' => rand(1000, 9999),
                        'status' => $status
                    ]),
                    'read_at' => $i % 2 === 0 ? $now : null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        Notification::insert($notifications);
    }
}
