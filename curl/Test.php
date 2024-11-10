<?php

namespace Curl;

use Utils\Console;

/**
 * 
 * class Test
 * 
 * It implements a simple assertion method to test the results of the curl session
 * Since php version ^7.0, the assert function is disabled by default so i made one just in case.
 *
 */
class Test
{

    public static function assert($condition, $description, $data = null)
    {
        $trace = debug_backtrace();
        // Get the caller file to add more precise info
        $callerAbsolutePath = $trace[0]['file'];

        // here we just grab from path, the name of the file ( not just absolute path )
        $pathArr = explode(DIRECTORY_SEPARATOR, $callerAbsolutePath);
        $callerFile = array_pop($pathArr);

        if (!$condition) {
            // The condition is not met, log a beautifull error and die
            $label = sprintf(Console::COLORS['red'], "[ERROR]");
            var_export("\n$label in $callerFile \n[MESSAGE] : $description\n[TYPE] : " . gettype($data) . "\n");
            die();
        }
        // The condition is met, log a beautifull success
        $label = sprintf(Console::COLORS['green'], "[SUCCESS]");
        var_export("\n$label in $callerFile \n[MESSAGE] : $description\n");
    }
};
