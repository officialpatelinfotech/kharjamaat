<?php
header('Content-Type: text/plain');
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "Searching for error_log files in public_html...\n\n";

function search_logs($dir) {
    $it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
    $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
    foreach ($files as $file) {
        if ($file->isFile() && (strtolower($file->getFilename()) === 'error_log' || strpos(strtolower($file->getFilename()), '.log') !== false)) {
            $mtime = date('Y-m-d H:i:s', $file->getMTime());
            echo "- " . $file->getPathname() . " (Size: " . $file->getSize() . " bytes, Modified: $mtime)\n";
            // Print last 5 lines if it is a recent error log
            if (time() - $file->getMTime() < 86400 * 30) {
                echo "  --- LAST 5 LINES ---\n";
                $lines = file($file->getPathname());
                $last = array_slice($lines, -5);
                foreach ($last as $l) {
                    echo "  " . trim($l) . "\n";
                }
                echo "  --------------------\n";
            }
        }
    }
}

search_logs(__DIR__);
echo "\nSearch completed.\n";
