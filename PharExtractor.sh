if [ "$1" != "" ]; then
    if [ -f ./bin/php7/bin/php ]; then
        ./bin/php7/bin/php -q PharExtractor.php $1
    elif type php 2>/dev/null; then
            php PharExtractor.php $1
    else
        echo "[ERROR] Couldn't find a working PHP binary, please install PHP on your machine to continue."
        exit 1
    fi
else
    echo "[ERROR] No target directory specified!"
fi
exit 1