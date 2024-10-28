<?php

namespace Utils;


/**
 * 
 * class Console
 * 
 * This class formats error logs and sends them to the error log ( php_error.log ) for debugging purposes.
 * Note : Each time the API is used, a log is generated in the php_error.log file.
 * 
 * @property array COLORS : An array containing the colors for the terminal
 * 
 * @method static string joinErrorLogs(array $array) Formats the error logs
 * @method static void log($message, $error, $data, $code) Sends the error logs to the error log
 * @method static void write(mixed $data) Writes in HTML
 * 
 */
class Console
{
    // LOVELY
    public const COLORS =
    [
        // styles
        // italic and blink may not work depending of your terminal
        'bold' => "\033[1m%s\033[0m",
        'dark' => "\033[2m%s\033[0m",
        'italic' => "\033[3m%s\033[0m",
        'underline' => "\033[4m%s\033[0m",
        'blink' => "\033[5m%s\033[0m",
        'reverse' => "\033[7m%s\033[0m",
        'concealed' => "\033[8m%s\033[0m",
        // foreground colors
        'black' => "\033[30m%s\033[0m",
        'red' => "\033[31m%s\033[0m",
        'green' => "\033[32m%s\033[0m",
        'yellow' => "\033[33m%s\033[0m",
        'blue' => "\033[34m%s\033[0m",
        'magenta' => "\033[35m%s\033[0m",
        'cyan' => "\033[36m%s\033[0m",
        'white' => "\033[37m%s\033[0m",
        // background colors
        'bg_black' => "\033[40m%s\033[0m",
        'bg_red' => "\033[41m%s\033[0m",
        'bg_green' => "\033[42m%s\033[0m",
        'bg_yellow' => "\033[43m%s\033[0m",
        'bg_blue' => "\033[44m%s\033[0m",
        'bg_magenta' => "\033[45m%s\033[0m",
        'bg_cyan' => "\033[46m%s\033[0m",
        'bg_white' => "\033[47m%s\033[0m",
    ];


    public static function joinErrorLogs(array $array): string
    {
        // We reduce the array to a string
        // If the data is too long, we truncate it to 100 characters
        $reduced = "\n" . array_reduce($array, function ($acc, $str) {
            $content = json_encode($str["value"],  JSON_UNESCAPED_UNICODE);
            if (strlen($content) > 100) {
                $content = substr($content, 0, 100) . "...";
            }
            return $acc . $str["label"] . ": " . $content . "\n";
        }, "");
        return $reduced;
    }

    public static function log($message, $error, $data, $code): void
    {
        $strs = [
            ["label" => "[MESSAGE]  ", "value" => $message],
            ["label" => "[ERROR]    ", "value" => $error],
            ["label" => "[DATA]     ", "value" => $data],
            ["label" => "[CODE]     ", "value" => $code]
        ];

        $log = self::joinErrorLogs($strs);

        error_log($log);
    }

    public static function write(mixed $data): void
    {
        echo "<pre>";
        var_export($data, false);
        echo "</pre>";
    }
}
