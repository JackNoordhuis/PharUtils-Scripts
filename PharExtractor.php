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

echo "\n--- PharUtils v0.0.1 by Jack Noordhuis — PharExtractor ---\n";
echo "\nAttempting to extract Phar...\n\n";

if(!isset($argv[1]) and $argv[1] !== "") {
	echo "[ERROR] No target file specified!\n\n";
	exit(1);
} elseif(!is_file($argv[1])) {
	echo "[ERROR] Invalid target file specified!\n\n";
	exit(1);
} else {
	$targetFile = $argv[1];
	if(!preg_match('/[a-zA-Z0-9]\.phar/', $targetFile)) {
		$targetFile .= ".phar";
	}
}

echo "\n";

$targetPath = __DIR__ . DIRECTORY_SEPARATOR . $targetFile;

$path = __DIR__ . DIRECTORY_SEPARATOR . str_replace(".phar", "", $targetFile);
@mkdir($path, 0755, true);

$start = microtime(true);
$fileCount = 0;

$pharPath = "phar://" . $targetPath;

echo "[PharExtractor] Preparing to extract files...\n";

foreach(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($pharPath)) as $file){
	$tempPath = $file->getPathname();
	@mkdir(dirname($path . str_replace($pharPath, "", $tempPath)), 0755, true);
	file_put_contents($path . str_replace($pharPath, "", $tempPath), file_get_contents($tempPath));
	echo "[PharExtractor] Extracted file: " . $file . "\n";
	$fileCount++;
}

$end = microtime(true);

echo "[PharExtractor] Done! Extracted {$fileCount} files in " . round($end - $start, 3) . "s!\n";

exit(0);