<?php
require __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Get an admin user and create token
$admin = \App\Models\User::where('email', 'nasr@gmail.com')->first();
if ($admin) {
    $token = $admin->createToken('test')->plainTextToken;
    echo "Token: " . $token . "\n\n";
    
    // Test the endpoint
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://morabackend.xcgulf.net/api/auth/admin/balance-requests");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer " . $token,
        "Accept: application/json"
    ]);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "HTTP Code: " . $httpCode . "\n";
    echo "Response: " . $response . "\n";
} else {
    echo "Admin user not found\n";
}
