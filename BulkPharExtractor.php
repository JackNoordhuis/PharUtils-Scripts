<?php

/*
 * PharUtils — PharExtractor.php
 *
 *    __             _        __                    _ _           _
 *    \ \  __ _  ___| | __ /\ \ \___   ___  _ __ __| | |__  _   _(_)___
 *     \ \/ _` |/ __| |/ //  \/ / _ \ / _ \| '__/ _` | '_ \| | | | / __|
 *  /\_/ / (_| | (__|   </ /\  / (_) | (_) | | | (_| | | | | |_| | \__ \
 *  \___/ \__,_|\___|_|\_\_\ \/ \___/ \___/|_|  \__,_|_| |_|\__,_|_|___/
 *
 * Usage: <target file>
 * Example: ExamplePhar.phar
 */

echo "\n--- PharUtils v0.0.1 by Jack Noordhuis — BulkPharExtractor ---\n";
echo "\nAttempting to extract archives from directory...\n\n";

if(!isset($argv[1]) and $argv[1] !== "") {
	echo "[ERROR] No target directory specified!\n\n";
	exit(1);
} elseif(!is_dir($argv[1])) {
	echo "[ERROR] Invalid target directory specified!\n\n";
	exit(1);
} else {
	$targetDir = $argv[1];
}

echo "\n";

$targetDir = __DIR__ . DIRECTORY_SEPARATOR . $targetDir . DIRECTORY_SEPARATOR;

$start = microtime(true);
$pharCount = 0;
$fileCount = 0;

foreach(scandir($targetDir) as $filename) {
	if(!in_array("phar", explode(".", $filename))) continue;

	$target = $targetDir . $filename;

	if(!is_file($target)) {
		echo "An error occurred while trying to locate {$target}!";
		continue;
	}

	$path = $targetDir . str_replace(".phar", "", $filename);
	@mkdir($path, 0755, true);

	$pharPath = "phar://" . $target;

	foreach(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($pharPath)) as $file){
		$tempPath = $file->getPathname();
		@mkdir(dirname($path . str_replace($pharPath, "", $tempPath)), 0755, true);
		file_put_contents($path . str_replace($pharPath, "", $tempPath), file_get_contents($tempPath));
		$fileCount++;
	}

	echo "[BulkPharExtractor] Extract files from {$target} successfully!\n";
	$pharCount++;
}

$end = microtime(true);

echo "[BulkPharExtractor] Done! Extracted {$fileCount} files from {$pharCount} archives in " . round($end - $start, 3) . "s!\n";

exit(0);