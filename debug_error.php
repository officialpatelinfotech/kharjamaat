<?php
header('Content-Type: text/plain');
ini_set('display_errors', 1);
error_reporting(E_ALL);

$log_file = __DIR__ . '/error_log';
if (file_exists($log_file)) {
    echo "--- Last 100 lines of root error_log ---\n";
    $lines = file($log_file);
    $last_lines = array_slice($lines, -100);
    echo implode("", $last_lines);
} else {
    echo "Root error_log not found.\n";
}

$app_log_dir = __DIR__ . '/application/logs/';
if (is_dir($app_log_dir)) {
    echo "\n--- application/logs/ directory contents ---\n";
    $files = scandir($app_log_dir);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        $path = $app_log_dir . $file;
        echo "$file (" . filesize($path) . " bytes, Modified: " . date("Y-m-d H:i:s", filemtime($path)) . ")\n";
    }
}
?>
