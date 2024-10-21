#!/bin/zsh

Watch for changes in the php_error_log file
tail -f ../../logs/php_error_log | while read line; do
    # Output the log line with the appropriate color
    echo "$(date +%H:%M:%S) : $line"
done
