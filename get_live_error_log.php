<?php
header('Content-Type: text/plain');
ini_set('display_errors', 1);
error_reporting(E_ALL);

$logFile = __DIR__ . '/error_log';
if (file_exists($logFile)) {
    echo "Reading last 100 lines of $logFile:\n\n";
    $lines = file($logFile);
    $last_lines = array_slice($lines, -100);
    echo implode("", $last_lines);
} else {
    echo "No error_log file found at $logFile\n";
}
