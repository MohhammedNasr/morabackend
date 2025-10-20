<?php
// CHANGE THIS SECRET!
define('WEBHOOK_SECRET', 'mora-deploy-secret-xyz123');

$payload = file_get_contents('php://input');
$signature = $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? '';

if ($signature) {
    $expected = 'sha256=' . hash_hmac('sha256', $payload, WEBHOOK_SECRET);
    if (!hash_equals($expected, $signature)) {
        http_response_code(403);
        die('Invalid signature');
    }
    
    $data = json_decode($payload, true);
    if (!isset($data['ref']) || $data['ref'] !== 'refs/heads/main') {
        die('Not main branch');
    }
}

$project = '/home3/xcgulfco/morabackend';
$logFile = '/home3/xcgulfco/deploy.log';

$commands = [
    "cd $project && git pull origin main 2>&1",
    "cd $project && composer install --no-dev --optimize-autoloader --no-interaction 2>&1",
    "cd $project && php artisan migrate --force 2>&1",
    // Clear caches first
    "cd $project && php artisan config:clear 2>&1",
    "cd $project && php artisan route:clear 2>&1",
    "cd $project && php artisan cache:clear 2>&1",
    "cd $project && php artisan view:clear 2>&1",
    // Then cache
    "cd $project && php artisan config:cache 2>&1",
    "cd $project && php artisan route:cache 2>&1",
    "cd $project && php artisan view:cache 2>&1",
];

$output = [
    '===========================================',
    date('Y-m-d H:i:s') . ' - Deploy Started',
    '==========================================='
];

$failed = false;
foreach ($commands as $index => $cmd) {
    $output[] = "\n[Command " . ($index + 1) . "]: $cmd";
    $out = [];
    $returnCode = 0;
    exec($cmd, $out, $returnCode);
    
    $output = array_merge($output, $out);
    
    if ($returnCode !== 0) {
        $output[] = "ERROR: Command failed with return code $returnCode";
        $failed = true;
        break; // Stop on first failure
    } else {
        $output[] = "✓ Success";
    }
}

if ($failed) {
    $output[] = "\n❌ Deploy FAILED";
    http_response_code(500);
} else {
    $output[] = "\n✅ Deploy Complete Successfully";
}

$logContent = implode("\n", $output) . "\n\n";
file_put_contents($logFile, $logContent, FILE_APPEND);
echo "<pre>" . $logContent . "</pre>";
?>
