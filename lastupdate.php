<?php

/* ---------- CONFIG ---------- */

$scanFolders = ['.']; // scan current folder
$allowedExt = ['html','htm','php','mp4','jpg','jpeg','png','gif','css','js'];
$cacheFile = __DIR__ . '/.lastupdate_cache';
$cacheLife = 300; // seconds (5 minutes)

/* ---------- CACHE ---------- */

if (file_exists($cacheFile) && time() - filemtime($cacheFile) < $cacheLife) {
    echo file_get_contents($cacheFile);
    exit;
}

/* ---------- SCAN FILES ---------- */

$latestTime = 0;

foreach ($scanFolders as $folder) {

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($folder, FilesystemIterator::SKIP_DOTS)
    );

    foreach ($iterator as $file) {

        if ($file->isFile()) {

            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

            if (in_array($ext, $allowedExt)) {

                $time = $file->getMTime();

                if ($time > $latestTime) {
                    $latestTime = $time;
                }
            }
        }
    }
}

/* ---------- TIMEZONE ---------- */

date_default_timezone_set('America/New_York');

$result = "Last Updated: " . date("F j, Y — g:i A T", $latestTime);

/* ---------- SAVE CACHE ---------- */

file_put_contents($cacheFile, $result);

echo $result;

?>
