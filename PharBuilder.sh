if [ "$1" != "" ]; then
    if [ -f ./bin/php7/bin/php ]; then
        ./bin/php7/bin/php -q PharBuilder.php $1 $2 $3
    elif type php 2>/dev/null; then
            php PharBuilder.php $1 $2 $3
    else
        echo "[ERROR] Couldn't find a working PHP binary, please install PHP on your machine to continue."
        exit 1
    fi
else
    echo "[ERROR] No target directory specified!"
fi
exit 1