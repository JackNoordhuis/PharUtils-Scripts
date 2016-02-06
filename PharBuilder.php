<?php

/*
    PharBuilder by 64FF00 (Twitter: @64FF00)

      888  888    .d8888b.      d8888  8888888888 8888888888 .d8888b.   .d8888b.
      888  888   d88P  Y88b    d8P888  888        888       d88P  Y88b d88P  Y88b
    888888888888 888          d8P 888  888        888       888    888 888    888
      888  888   888d888b.   d8P  888  8888888    8888888   888    888 888    888
      888  888   888P "Y88b d88   888  888        888       888    888 888    888
    888888888888 888    888 8888888888 888        888       888    888 888    888
      888  888   Y88b  d88P       888  888        888       Y88b  d88P Y88b  d88P
      888  888    "Y8888P"        888  888        888        "Y8888P"   "Y8888P"
*/

echo "--- PharBuilder by #64FF00 --- \n \n";

if(!isset($argv[1]) || !is_dir($argv[1]))
{
    echo "[ERROR] Please specify a valid directory name. \n \n";

    exit(1);
}

$filePath = $argv[1] . ".phar";

if(file_exists($filePath))
    @unlink($filePath);

$phar = new Phar($filePath);

$phar->startBuffering();

echo "[64FF00] Setting custom Phar archive metadata... \n";

$phar->setMetadata(
    [
        "name" => $argv[1],
        "creationDate" => time()
    ]
);

/*
 * DevTools: require("phar://". __FILE__ ."/src/DevTools/ConsoleScript.php");
 */
$phar->setStub('<?php echo "Hello, world! \n This PHAR archive has been generated using PharBuilder by #64FF00! :D"; __HALT_COMPILER();');

$phar->setSignatureAlgorithm(Phar::SHA512);

/** @var \SplFileInfo $splFileInfo */
foreach(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($argv[1])) as $splFileInfo)
{
    $tempDirectory = str_replace($argv[1], '', $splFileInfo->getPathname());

    if($splFileInfo->getFilename() === '.' || $splFileInfo->getFilename() === '..')
        continue;

    echo "[64FF00] Adding file: " . $splFileInfo->getPathname() . "\n";

    $phar->addFile($splFileInfo->getPathname(), $tempDirectory);
}

echo "[64FF00] Compressing files... \n";

$phar->compressFiles(\Phar::GZ);

echo "[64FF00] Done! \n";

$phar->stopBuffering();

exit(0);