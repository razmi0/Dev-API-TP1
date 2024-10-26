<?php

namespace Utils;


/**
 * 
 * class Console
 * 
 * This class formats error logs and sends them to the error log ( php_error.log ) for debugging purposes.
 * Note : Each time the API is used, a log is generated in the php_error.log file.
 * 
 */
class Console
{

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
