<?php
use Core\Autoload;

error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('America/New_York');

/**
 * Setup basic constants
 **/
define('DS', DIRECTORY_SEPARATOR);
define('APP_PATH', dirname(__FILE__) . DS . '..' . DS . 'app' . DS);
define('SRC_PATH', APP_PATH . 'src' . DS);
define('LIB_PATH', APP_PATH . '..' . DS . 'library' . DS);
define('TEST_HELPER_PATH', dirname(__FILE__) . DS . 'helpers' . DS);

/**
 * Autoload everything
 **/
require LIB_PATH . 'Core/Autoload.php';

$loader = new Autoload(array(
    LIB_PATH,
    SRC_PATH,
    TEST_HELPER_PATH
));

/* Include composer autoloader */
require __DIR__ . '/../vendor/autoload.php';