<?php
use Core\Autoload;

/**
 * Basic PHP settings
 */
if (getenv('environment') == 'local') {
    ini_set('display_errors', 1);
}

error_reporting(E_ALL);
date_default_timezone_set('America/New_York');

/**
 * Setup basic constants
 **/
define('DS', DIRECTORY_SEPARATOR);
define('APP_PATH', dirname(__FILE__) . DS);
define('SRC_PATH', APP_PATH . 'src' . DS);
define('LIB_PATH', APP_PATH . '..' . DS . 'library' . DS);

/**
 * Autoload everything
 **/
require LIB_PATH . 'Core/Autoload.php';

$loader = new Autoload(array(
    LIB_PATH,
    SRC_PATH
));
