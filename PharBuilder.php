<?php

/*
 * PharUtils — PharBuilder.php
 *
 *    __             _        __                    _ _           _
 *    \ \  __ _  ___| | __ /\ \ \___   ___  _ __ __| | |__  _   _(_)___
 *     \ \/ _` |/ __| |/ //  \/ / _ \ / _ \| '__/ _` | '_ \| | | | / __|
 *  /\_/ / (_| | (__|   </ /\  / (_) | (_) | | | (_| | | | | |_| | \__ \
 *  \___/ \__,_|\___|_|\_\_\ \/ \___/ \___/|_|  \__,_|_| |_|\__,_|_|___/
 *
 * Usage: <target directory> <filename> <stub>
 * Example: YourFolder ExamplePhar.phar <?php 'echo "Hello World!"; __HALT_COMPILER();'
 */

echo "\n--- PharUtils v0.0.1 by Jack Noordhuis — PharBuilder ---\n";
echo "\nAttempting to construct Phar...\n\n";

if(!isset($argv[1]) and $argv[1] !== "") {
	echo "[ERROR] No target directory specified!\n\n";
	exit(1);
} elseif(!is_dir($argv[1])) {
	echo "[ERROR] Invalid target directory specified!\n\n";
	var_dump($argv[1]);
	exit(1);
} else {
	$targetPath = $argv[1];
}

if(!isset($argv[2]) and $argv[2] !== "") {
	echo "[WARNING] No custom name specified, using default file name...\n";
	$filename = "YourPhar.phar";
} else {
	$filename = $argv[2];
	if(preg_match('/[a-zA-Z0-9]\.phar/', $filename) !== true) {
		$filename .= ".phar";
	}
}

if(!isset($argv[3]) and $argv[3] !== "") {
	echo "[INFO] No custom stub provided, using default stub...\n";
	$stub = '<?php echo "Hello, world!\nThis Phar archive was created using PharBuilder v0.0.1 by JackNoordhuis! 
	https://github.com/JackNoordhuis/PharUtils";
	__HALT_COMPILER();';
} else {
	$stub = $argv[3]; // No checks on the stub for this version :V
}

$path = __DIR__ . DIRECTORY_SEPARATOR . $filename;

if(is_file($path)) {
	echo "[WARNING] Phar called '{$filename}' already exists! Over writing...\n";
	@unlink($path);
}

echo "\n";

$start = microtime(true);
$fileCount = 0;

$phar = new \Phar($path);

echo "[PharBuilder] Setting custom Phar archive metadata... \n";

$phar->setMetadata(["name" => $argv[2], "creationDate" => time()]);

$phar->setStub($stub);

$phar->setSignatureAlgorithm(Phar::SHA512);

$phar->startBuffering();

/** @var \SplFileInfo $splFileInfo */
foreach(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($targetPath)) as $file) {
	$tempPath = ltrim(str_replace(["\\", $targetPath], ["/", ""], $file), "/");
	if($tempPath{0} === "." or strpos($tempPath, "/.") !== false) continue;
	$phar->addFile($file, $tempPath);
	$fileCount++;
	echo "[PharBuilder] Added file: " . $file . "\n";
}

foreach($phar as $file => $info){
	/** @var \PharFileInfo $finfo */
	if($info->getSize() > (1024 * 512)){
		$info->compress(\Phar::GZ);
	}
}

$end = microtime(true);

echo "[PharBuilder] Done! Archived {$fileCount} files in " . round($end - $start, 3) . "s!\n";

$phar->stopBuffering();

exit(0);