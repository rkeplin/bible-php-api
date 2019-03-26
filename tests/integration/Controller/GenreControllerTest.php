<?php
namespace Test\Integration\Controller;

use Controller\GenreController;
use Core\Domain\Entity\Collection;
use Domain\Entity\GenreEntity;
use Test\IntegrationTestCase;

/**
 * GenreControllerTest
 *
 * @package \Test\Integration\Controller
 */
class GenreControllerTest extends IntegrationTestCase
{
    public function testGetActionReturnsOne()
    {
        $controller = new GenreController();
        $controller->setRouteParams(array(
            'genreId' => 1
        ));
        $response = $controller->getAction();

        $this->assertEquals(200, $response->getCode());
        $this->assertEquals('OK', $response->getLabel());
        $this->assertTrue($response->getContent() instanceof GenreEntity);

        $this->assertEquals('Law', $response->getContent()->getName());
    }

    public function testGetActionReturnsAll()
    {
        $controller = new GenreController();
        $response = $controller->getAction();

        $this->assertEquals(200, $response->getCode());
        $this->assertEquals('OK', $response->getLabel());
        $this->assertTrue($response->getContent() instanceof Collection);

        $items = $response->getContent()->getItems();

        $this->assertCount(8, $items);
        $this->assertEquals('Law', $items[0]['name']);
    }
}

