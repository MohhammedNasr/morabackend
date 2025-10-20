<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Find or create a store branch
$storeBranch = \App\Models\StoreBranch::first();

if (!$storeBranch) {
    echo "No store branches found. Looking for existing test store or creating new one...\n";
    
    // Find or create a store owner user
    $storeOwnerRole = \App\Models\Role::where('slug', 'store-owner')->first();
    if (!$storeOwnerRole) {
        echo "Store owner role not found!\n";
        exit(1);
    }
    
    $owner = \App\Models\User::where('role_id', $storeOwnerRole->id)->first();
    if (!$owner) {
        echo "Creating a test store owner user...\n";
        $owner = \App\Models\User::create([
            'name' => 'Test Store Owner',
            'email' => 'teststoreowner@example.com',
            'phone' => '966500000099',
            'password' => bcrypt('password123'),
            'role_id' => $storeOwnerRole->id,
            'is_active' => true,
            'phone_verified_at' => now(),
        ]);
    }
    
    // Find or create a test store
    $store = \App\Models\Store::where('email', 'teststore@example.com')->first();
    if (!$store) {
        echo "Creating a test store...\n";
        $store = \App\Models\Store::create([
            'owner_id' => $owner->id,
            'name' => 'Test Store',
            'email' => 'teststore@example.com',
            'phone' => '966500000001',
            'address' => 'Test Address',
            'city_id' => 1,
            'store_type_id' => 1,
            'tax_record' => '1234567890',
            'commercial_record' => '9876543210',
            'is_active' => true,
            'is_verified' => true,
        ]);
    } else {
        echo "Found existing test store.\n";
    }
    
    // Create a test branch
    echo "Creating a test branch...\n";
    $storeBranch = \App\Models\StoreBranch::create([
        'store_id' => $store->id,
        'name' => 'Main Branch',
        'address' => 'Main Branch Address',
        'street_name' => 'Main Street',
        'building_number' => '123',
        'city_id' => 1,
        'phone' => '966500000002',
        'latitude' => 24.7136,
        'longitude' => 46.6753,
        'is_active' => true,
    ]);
    
    echo "Created test branch.\n";
}

// Create a balance request
$balanceRequest = \App\Models\BranchBalanceRequest::create([
    'store_branch_id' => $storeBranch->id,
    'store_id' => $storeBranch->store_id,
    'requested_balance_limit' => 50000.00,
    'business_type' => 'Retail',
    'years_in_business' => 5,
    'average_monthly_revenue' => 100000.00,
    'number_of_employees' => 10,
    'business_description' => 'Test retail business',
    'tax_registration_number' => '123456789',
    'commercial_registration_number' => '987654321',
    'bank_account_number' => '1234567890',
    'bank_name' => 'Test Bank',
    'iban' => 'SA1234567890123456789012',
    'contact_person_name' => 'John Doe',
    'contact_person_phone' => '966500000003',
    'contact_person_email' => 'john@example.com',
    'contact_person_position' => 'Manager',
    'status' => 'pending',
]);

echo "✅ Successfully created balance request!\n";
echo "ID: " . $balanceRequest->id . "\n";
echo "Branch: " . $storeBranch->name . "\n";
echo "Store: " . $storeBranch->store->name . "\n";
echo "Requested Limit: " . $balanceRequest->requested_balance_limit . "\n";
echo "Status: " . $balanceRequest->status . "\n";
