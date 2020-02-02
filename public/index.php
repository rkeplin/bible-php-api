<?php
include '../app/bootstrap.php';
use Core\Application;

$config = include APP_PATH . 'configs' . DS . 'default.php';

$app = new Application();

try {
    $app->setup($config);
    $app->run();
} catch(Exception $e) {
    $app->displayError($e);
}
