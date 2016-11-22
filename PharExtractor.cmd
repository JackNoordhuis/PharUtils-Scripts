@echo off
SET PHP_DIR=\bin\php7\bin\php
TITLE --- PharExtractor v0.0.1 by Jack Noordhuis ---
%PHP_DIR%\php.exe -c %PHP_DIR% PharExtractor.php YourPhar.phar
pause