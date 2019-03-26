<?php
namespace Test\Integration\Controller;

use Controller\BookController;
use Core\Domain\Entity\Collection;
use Domain\Entity\BookEntity;
use Test\IntegrationTestCase;

/**
 * BookControllerTest
 *
 * @package \Test\Integration\Controller
 */
class BookControllerTest extends IntegrationTestCase
{
    public function testGetActionReturnsAllBooks()
    {
        $controller = new BookController();
        $response = $controller->getAction();

        $this->assertEquals(200, $response->getCode());
        $this->assertEquals('OK', $response->getLabel());
        $this->assertTrue($response->getContent() instanceof Collection);

        $content = $response->getContent()->getItems();

        $this->assertCount(66, $content);
        $this->assertEquals('Genesis', $content[0]['name']);
        $this->assertEquals('Revelation', $content[65]['name']);
    }

    public function testGetActionReturnsOneBook()
    {
        $controller = new BookController();
        $controller->setRouteParams(array(
            'bookId' => 12
        ));

        $response = $controller->getAction();

        $this->assertEquals(200, $response->getCode());
        $this->assertEquals('OK', $response->getLabel());
        $this->assertTrue($response->getContent() instanceof BookEntity);

        $content = $response->getContent();
        $this->assertEquals('2 Kings', $content->getName());
    }

    public function testChaptersAction()
    {
        $controller = new BookController();
        $controller->setRouteParams(array(
            'bookId' => 12
        ));

        $response = $controller->chaptersAction();

        $this->assertEquals(200, $response->getCode());
        $this->assertEquals('OK', $response->getLabel());
        $this->assertIsArray($response->getContent());
        $this->assertCount(25, $response->getContent());
    }
}

