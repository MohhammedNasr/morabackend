<?php
// CHANGE THIS SECRET!
define('WEBHOOK_SECRET', 'mora-deploy-secret-xyz123');

// Catch all errors
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    $logFile = '/home3/xcgulfco/deploy-error.log';
    $error = date('Y-m-d H:i:s') . " ERROR: $errstr in $errfile:$errline\n";
    file_put_contents($logFile, $error, FILE_APPEND);
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

try {
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
} catch (Exception $e) {
    $logFile = '/home3/xcgulfco/deploy-error.log';
    file_put_contents($logFile, date('Y-m-d H:i:s') . " EXCEPTION: " . $e->getMessage() . "\n", FILE_APPEND);
    http_response_code(500);
    die("Error: " . $e->getMessage());
}

$project = '/home3/xcgulfco/morabackend';
$logFile = '/home3/xcgulfco/deploy.log';

// Basic error handling
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Verify project directory exists
if (!is_dir($project)) {
    $error = "ERROR: Project directory does not exist: $project\n";
    file_put_contents($logFile, date('Y-m-d H:i:s') . " - $error", FILE_APPEND);
    http_response_code(500);
    die($error);
}

$commands = [
    // Reset to match remote exactly (discard any local changes)
    "cd $project && git fetch origin main 2>&1",
    "cd $project && git reset --hard origin/main 2>&1",
    "cd $project && git clean -fd -e public/deploy.php 2>&1", // Remove untracked files except deploy.php
    "cd $project && cp deploy.php public/deploy.php 2>&1", // Ensure deploy.php is in public
    "cd $project && HOME=/home3/xcgulfco COMPOSER_HOME=/home3/xcgulfco/.composer composer install --no-dev --optimize-autoloader --no-interaction 2>&1",
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

try {
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
    
} catch (Exception $e) {
    $error = date('Y-m-d H:i:s') . " FATAL ERROR: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n\n";
    file_put_contents('/home3/xcgulfco/deploy-error.log', $error, FILE_APPEND);
    http_response_code(500);
    die("Fatal error during deployment: " . $e->getMessage());
}
?>
