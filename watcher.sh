#!/bin/zsh
# Listen to changes in the php_error_log file
echo $(date +%d.%m.%y-%H:%M:%S)
tail -f ../../logs/php_error_log