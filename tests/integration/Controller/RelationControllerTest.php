<?php
namespace Test\Integration\Controller;

use Controller\RelationController;
use Test\IntegrationTestCase;

/**
 * RelationControllerTest
 *
 * @package \Test\Integration\Controller
 */
class RelationControllerTest extends IntegrationTestCase
{
    public function testGetAction()
    {
        $controller = new RelationController();
        $controller->setRouteParams(array(
            'verseId' => 1001001
        ));
        $response = $controller->getAction();

        $this->assertEquals(200, $response->getCode());
        $this->assertEquals('OK', $response->getLabel());
        $this->assertIsArray($response->getContent());
        $this->assertCount(61, $response->getContent());
    }
}

