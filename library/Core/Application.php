<?php
namespace Core;

use Controller\ErrorController;
use Core\Domain\Mapper\AbstractMapper;
use Core\Domain\Mapper\AbstractMongoMapper;
use Core\Domain\Service\SessionService;
use Core\Http\HttpNotFoundException;
use MongoDB\Client;
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

        if ($config['environment'] !== 'production') {
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
        }

        if(isset($config['mysql'], $config['mysql']['db_host'])) {
            $db = new PDO("mysql:host={$config['mysql']['db_host']};dbname={$config['mysql']['db_name']}", $config['mysql']['db_user'], $config['mysql']['db_pass']);
            AbstractMapper::setDefaultDb($db);
        }

        if (isset($config['mongo'], $config['mongo']['host'])) {
            $client = new Client(sprintf('mongodb://%s:27017', $config['mongo']['host']), array(
                'username' => $config['mongo']['user'],
                'password' => $config['mongo']['pass'],
            ));
            AbstractMongoMapper::setDefaultClient($client);
        }

        if (isset($config['session'])) {
            if (isset($config['session']['name'])) {
                SessionService::setDefaultSessionName($config['session']['name']);
            }
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
        $httpMethod = strtoupper($_SERVER['REQUEST_METHOD']);

        foreach ($routes as $route => $routeOptions) {
            if (preg_match('/^' . str_replace('/', '\/', $route) . '$/', $url, $matches)) {
                if (!isset($routeOptions[$httpMethod])) {
                    throw new HttpNotFoundException('Page not found.');
                }

                $options = $routeOptions[$httpMethod];

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
            $controller->setParams($_GET);
            $controller->setRouteParams($routeParams);

            $json = json_decode(file_get_contents('php://input'), true);

            if (is_array($json)) {
                $controller->setJson($json);
            }

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
        $response = $controller->indexAction($e);
        $response->send();
    }
}
