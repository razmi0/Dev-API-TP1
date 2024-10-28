#!/bin/zsh

# Watch for changes in the access_log file
tail -f ../../logs/access_log | while read line; do
    # Output the log line with the appropriate color
    echo "$(date +%H:%M:%S) : $line"
done
