<?php
namespace Test\Integration\Controller;

use Controller\TextController;
use Core\Domain\Entity\Collection;
use Domain\Entity\TextEntity;
use Test\IntegrationTestCase;

/**
 * TextControllerTest
 *
 * @package \Test\Integration\Controller
 */
class TextControllerTest extends IntegrationTestCase
{
    public function testGetActionReturnsSpecificVerse()
    {
        $controller = new TextController();
        $controller->setRouteParams(array(
            'verseId' => 1001001
        ));
        $response = $controller->getAction();

        $this->assertEquals(200, $response->getCode());
        $this->assertEquals('OK', $response->getLabel());
        $this->assertTrue($response->getContent() instanceof TextEntity);
        $this->assertEquals('In the beginning God created the heaven and the earth.', $response->getContent()->getVerse());
    }

    public function testGetActionReturnsSpecificBookAndChapter()
    {
        $controller = new TextController();
        $controller->setRouteParams(array(
            'bookId' => 1,
            'chapterId' => 1
        ));
        $response = $controller->getAction();

        $this->assertEquals(200, $response->getCode());
        $this->assertEquals('OK', $response->getLabel());
        $this->assertTrue($response->getContent() instanceof Collection);
        $this->assertCount(31, $response->getContent()->getItems());
    }
}

