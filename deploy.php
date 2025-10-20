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

$output = [date('Y-m-d H:i:s') . ' - Deploy Started'];
foreach ($commands as $cmd) {
    exec($cmd, $out);
    $output = array_merge($output, $out);
}
$output[] = 'Deploy Complete';

file_put_contents('/home/xcgulfco/deploy.log', implode("\n", $output) . "\n\n", FILE_APPEND);
echo "<pre>" . implode("\n", $output) . "</pre>";
?>
