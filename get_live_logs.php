<?php
header('Content-Type: text/plain');
ini_set('display_errors', 1);
error_reporting(E_ALL);

$logDir = __DIR__ . '/application/logs/';
echo "Listing files in $logDir:\n\n";

if (is_dir($logDir)) {
    $files = scandir($logDir);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        $filePath = $logDir . $file;
        $size = filesize($filePath);
        $mtime = date('Y-m-d H:i:s', filemtime($filePath));
        echo "- $file (Size: $size bytes, Modified: $mtime)\n";
    }
    
    // Find the latest CodeIgniter log file (typically starts with log-Y-m-d.php)
    $latestLog = null;
    $latestTime = 0;
    foreach ($files as $file) {
        if (strpos($file, 'log-') === 0 && substr($file, -4) === '.php') {
            $filePath = $logDir . $file;
            $mtime = filemtime($filePath);
            if ($mtime > $latestTime) {
                $latestTime = $mtime;
                $latestLog = $file;
            }
        }
    }
    
    if ($latestLog) {
        echo "\nContent of latest log file ($latestLog):\n\n";
        $content = file_get_contents($logDir . $latestLog);
        // Clean/obscure PHP open tags if needed, or print directly
        echo substr($content, 0, 5000); // Print first 5000 bytes
    } else {
        echo "\nNo CodeIgniter log files found.\n";
    }
} else {
    echo "Logs directory does not exist at $logDir\n";
}
