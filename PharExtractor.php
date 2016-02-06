<?php

/*
    PharExtractor by 64FF00 (Twitter: @64FF00)

      888  888    .d8888b.      d8888  8888888888 8888888888 .d8888b.   .d8888b.
      888  888   d88P  Y88b    d8P888  888        888       d88P  Y88b d88P  Y88b
    888888888888 888          d8P 888  888        888       888    888 888    888
      888  888   888d888b.   d8P  888  8888888    8888888   888    888 888    888
      888  888   888P "Y88b d88   888  888        888       888    888 888    888
    888888888888 888    888 8888888888 888        888       888    888 888    888
      888  888   Y88b  d88P       888  888        888       Y88b  d88P Y88b  d88P
      888  888    "Y8888P"        888  888        888        "Y8888P"   "Y8888P"
*/

echo "--- PharExtractor by #64FF00 --- \n \n";

if(!isset($argv[1]) || !file_exists($argv[1] . ".phar"))
{
    echo "[ERROR] Please specify a valid file name. \n \n";

    exit(1);
}

if(pathinfo($argv[1], PATHINFO_EXTENSION) === "phar")
{
    $fileName = $argv[1];
}
else
{
    $fileName = $argv[1] . ".phar";
}

$phar = new Phar($fileName);

$pharPath = "phar://" . $phar->getPath();

$currentDirectory = dirname($phar->getPath()) . DIRECTORY_SEPARATOR . $argv[1];

echo "[64FF00] Extracting files... Please wait... \n";

/** @var \SplFileInfo $splFileInfo */
foreach(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($pharPath)) as $splFileInfo)
{
    $tempFilePath = $currentDirectory . str_replace($pharPath, '', $splFileInfo->getPathname());

    $tempDirectory = dirname($tempFilePath);

    if(!file_exists($tempDirectory))
    {
        @mkdir($tempDirectory, 0755, true);

        echo "[64FF00] New directory created: $tempDirectory \n";
    }

    file_put_contents($tempFilePath, file_get_contents($splFileInfo->getPathname()));
}

echo "[64FF00] Done! \n";

exit(0);