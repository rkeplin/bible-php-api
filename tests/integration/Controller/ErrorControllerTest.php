<?php
namespace Test\Integration\Controller;

use Controller\ErrorController;
use Core\Http\HttpNotFoundException;
use Test\IntegrationTestCase;

/**
 * ErrorControllerTest
 *
 * @package \Test\Integration\Controller
 */
class ErrorControllerTest extends IntegrationTestCase
{
    public function testGetAction()
    {
        $exception = new HttpNotFoundException();

        $controller = new ErrorController();
        $response = $controller->indexAction($exception);

        $this->assertEquals(404, $response->getCode());
        $this->assertEquals('Not Found', $response->getLabel());
    }
}

