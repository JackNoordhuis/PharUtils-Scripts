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

 echo "\n--- PharUtils v0.0.1 by Jack Noordhuis — PluginAPIUpdate ---\n";
echo "\nAttempting to update plugin API's in directory...\n\n";

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
$pluginCount = 0;

$files = findFiles($targetDir);
foreach($files as $file) {
	if(is_file($file)) {
		$contents = yaml_parse(file_get_contents($file));
		$contents["api"] = ["3.0.0", "3.0.0-ALPHA1", "3.0.0-ALPHA2"];
		$pluginName = $contents["name"];
		file_put_contents($file, yaml_emit($contents, YAML_UTF8_ENCODING));
		echo "Updated {$pluginName}('s') API!\n";
		$pluginCount++;
	} else {
		echo "Attempted to change API version of invalid file! Name: {$file}\n";
	}

}

$end = microtime(true);

echo "[PluginAPIUpdate] Done! Changed {$pluginCount} plugins API versions in " . round($end - $start, 3) . "s!\n";

function findFiles($dir) {
	$files = [];

	$path = realpath($dir);

	$objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
	foreach($objects as $object){
	    if(strtolower($object->getFilename()) === "plugin.yml") {
	    	$files[] = $object->getPathname();
	    } 
	}

	// $invalid = ["", " ", ".", ".."];
	// $files = [];
	// if(is_dir($dir)) {
	// 	foreach(scandir($dir) as $filename) {
	// 		if(in_array($filename, $invalid)) continue;
	// 		var_dump($filename);
	// 		if(is_dir($filename)) {
	// 			$files = array_merge($files, findFiles($filename));
	// 		} elseif(is_file($filename)) {
	// 			if(stripos($filename, "plugin.yml") !== false) {
	// 				$files[] = $filename;
	// 			}
	// 		}
	// 	}
	// }
	return $files;
}

exit(0);