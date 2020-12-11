<?php


namespace Models;


use Error;
use PDOException;

class Logger
{
    /**
     * @param Error | PDOException $error
     * @param string $file
     */
    public static function logError($error, $file) {
        $now = date('Y-m-d - H:s:i - ', time());
        $path = __ROOT__ . 'log/' . $file . '-error.txt';
        $log = $now . $error->getMessage() . ' ' . $error->getFile() . ':' . $error->getLine();

        if (file_exists($path)) {
            $content  = file_get_contents($path);
            $content .= PHP_EOL .$log;
            file_put_contents($path, $content);
        } else {
            $logFile = fopen($path, 'w');
            fwrite($logFile, $log);
            fclose($logFile);
        }
    }

    public static function logInvalidLogin($username, $message) {
        $now = date('Y-m-d - H:s:i - ', time());
        $path = __ROOT__ . 'log/login-failed-attempts.txt';
        $log = $now . $username . ' - ' . $message;

        $content  = file_get_contents($path);
        $content .= PHP_EOL . $log;
        file_put_contents($path, $content);
    }
}