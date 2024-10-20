#!/bin/zsh

# Define color codes for different log levels
red="\033[0;31m"
yellow="\033[0;33m"
green="\033[0;32m"
reset="\033[0m"
white="\033[0;37m"

# Watch for changes in the php_error_log file
tail -f ../../logs/php_error_log | while read line; do
    # Parse the log line to determine the log level
    if [[ $line =~ ^\[emerg\] ]]; then
        color=$red
    elif [[ $line =~ ^\[alert\] ]]; then
        color=$red
    elif [[ $line =~ ^\[crit\] ]]; then
        color=$red
    elif [[ $line =~ ^\[err\] ]]; then
        color=$yellow
    elif [[ $line =~ ^\[warning\] ]]; then
        color=$yellow
    else
        color=$write
    fi

    # Output the log line with the appropriate color
    echo "$(date +%d.%m.%y-%H:%M:%S) ${color}$line${reset}"
done