<?php
namespace Core;

use Controller\ErrorController;
use Core\Domain\Mapper\AbstractMapper;
use Core\Http\HttpNotFoundException;
use PDO;
use Exception;

/**
 * Core MVC Framework.
 *
 * @copyright Copyright (c) 2012 Rob Keplin.
 * @license TBD
 **/
class Application
{
    /**
     * @var array
     **/
    public static $config;

    /**
     * Sets up the application based on the configuration given in the parameter.
     *
     * @param array $config
     * @return void
     **/
    public function setup(array $config)
    {
        self::$config = $config;

        if(isset($config['mysql']['db_host'])) {
            $db = new PDO("mysql:host={$config['mysql']['db_host']};dbname={$config['mysql']['db_name']}", $config['mysql']['db_user'], $config['mysql']['db_pass']);
            AbstractMapper::setDefaultDb($db);
        }
    }

    /**
     * Runs the application by finding the controller from the request URI, and
     * instantiating it.
     *
     * Passes along information from the controller to the view.
     *
     * @param void
     * @throws \Core\Http\HttpNotFoundException
     * @return void
     **/
    public function run()
    {
        $url = str_replace($_SERVER['CONTEXT_PREFIX'], '', $_SERVER['QUERY_STRING']);
        $url = str_replace('_url=', '', $url);

        $url = explode('&', $url);
        $url = $url[0];

        $routes = Application::$config['routes'];
        $routeFound = false;

        $routeParams = array();

        foreach ($routes as $route => $options) {
            if (preg_match('/^' . str_replace('/', '\/', $route) . '$/', $url, $matches)) {

                if (isset($options[2]) && is_array($options[2])) {
                    foreach ($options[2] as $index => $routeKey) {
                        $routeParams[$routeKey] = $matches[$index + 1];
                    }
                }

                $routeFound = $options;
                break;
            }
        }

        if($routeFound === false) {
            throw new HttpNotFoundException('Page not found.');
        }

        $query['controller'] = $routeFound[0];
        $query['action'] = $routeFound[1];

        $controller = '\\Controller\\' . ucwords($query['controller']) . 'Controller';
        $action = $query['action'] . 'Action';

        $page_found = false;

        if(class_exists($controller)) {
            $controller = new $controller();
            $controller->setPost($_POST);
            $controller->setRouteParams($routeParams);
            $controller->setParams($_GET);

            if(method_exists($controller, $action)) {
                $response = $controller->$action();
                $response->send();

                $page_found = true;
            }
        }

        if(!$page_found) {
            throw new HttpNotFoundException('Page not found.');
        }
    }

    /**
     * Displays the error controller, passing along an exception to it.
     *
     * @param Exception $e
     * @return void
     **/
    public function displayError(Exception $e)
    {
        $controller = new ErrorController();
        $vars = $controller->indexAction($e);

        $script_folder = APP_PATH . 'views' . DS;
        $script = 'error/index.php';

        $view = new View($script_folder);
        $view->setVars($vars);
        $view->render($script);
    }
}
