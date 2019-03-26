<?php
namespace Controller;

use Core\Application;
use Core\Controller;
use Core\Http\HttpException;
use Core\Http\HttpInternalErrorException;
use Core\Http\Response;
use Exception;

/**
 * Class ErrorController
 *
 * @package Controller
 */
class ErrorController extends Controller
{
    /**
     * Displays an error for the user to see.
     *
     * @param \Exception $e
     * @return \Core\Http\Response
     **/
    public function indexAction(Exception $e)
    {
        if (!($e instanceof HttpException)) {
            $e = new HttpInternalErrorException($e->getMessage(), $e->getCode(), $e);
        }

        $error = $e->toArray();

        if (Application::$config['environment'] != 'local') {
            unset($error['debug']);
        }

        return new Response($error, $e->getHttpCode(), $e->getTitleMessage());
    }
}
