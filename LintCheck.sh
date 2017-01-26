#!/bin/bash
if [ "$1" != "" ]; then
    echo "[LintCheck] Running lint check..."
    for file in 'find .' do
		EXTENSION="${file##*.}"
		if [ "$EXTENSION" == "php" ] || [ "$EXTENSION" == "phtml" ]
			then
			RESULTS='php -. $file'

			if [ "$RESULTS" != "No syntax errors detected in $file" ]
				then
				echo $RESULTS
			fi
		fi
	done

else
    echo "[ERROR] No target directory specified!"
fi
exit 1