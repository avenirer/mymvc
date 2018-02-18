<?php

date_default_timezone_set('Europe/Bucharest');
define('ROOT_PATH', dirname(realpath(__FILE__)));
define('APP_PATH', ROOT_PATH.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'App');
define('CORE_PATH', ROOT_PATH.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Core');
define('CONFIG_PATH', APP_PATH.DIRECTORY_SEPARATOR.'Config');
define('BASE_URL', 'http://mymvc.localhost/');

require_once dirname(__DIR__) . '/vendor/autoload.php';
/*
spl_autoload_register(function ($class) {
    $root = dirname(__DIR__);
    $file = $root . '/' . str_replace('\\', '/', $class) . '.php';
    if(is_readable($file)) {
        require $root . '/' . str_replace('\\', '/', $class) . '.php';
    }
});
*/
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');

$router = new Core\Router();
$router->dispatch($_SERVER['QUERY_STRING']);
