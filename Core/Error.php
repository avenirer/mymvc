<?php
namespace Core;

use App\Config\General;

class Error
{
    public static function errorHandler($level, $message, $file, $line)
    {
        if(error_reporting() != 0) {
            throw new \ErrorException($message, 0, $level, $file, $line);
        }
    }

    /**
     * @param $exception
     * @var $exception \Exception
     */
    public static function exceptionHandler($exception)
    {
        $code = $exception->getCode();
        if($code != 404) {
            $code = 500;
        }
        http_response_code($code);

        if (General::SHOW_ERRORS) {
            echo "<h1>Fatal error</h1>";
            echo "<p>Uncaught exception: '" . get_class($exception) . "'</p>";
            echo "<p>Message: '" . $exception->getMessage() . "'</p>";
            echo "<p>Stack trace: <pre>" . $exception->getTraceAsString() . "</pre></p>";
            echo "<p>Thrown in '" . $exception->getFile() . "' on line " .
                $exception->getLine() . "</p>";
        }
        else {
            $log = dirname(__DIR__) . '/writable/logs/' . date('Y-m-d'). '.txt';
            ini_set('error_log', $log);
            $message = "Uncaught exception: '" . get_class($exception) . "'";
            $message .= " with message '" . $exception->getMessage() . "'";
            $message .= "\nStack trace: " . $exception->getTraceAsString();
            $message .= "\nThrown in '" . $exception->getFile() . "' on line " .
            $message .= "\n\n";
                $exception->getLine();
            error_log($message);
            View::renderTemplate("$code.html");
        }
    }
}
