<?php
namespace Test\Integration\Controller;

use Controller\TranslationController;
use Core\Domain\Entity\Collection;
use Domain\Entity\TranslationEntity;
use Test\IntegrationTestCase;

/**
 * TranslationControllerTest
 *
 * @package \Test\Integration\Controller
 */
class TranslationControllerTest extends IntegrationTestCase
{
    public function testGetActionReturnsAllTranslations()
    {
        $controller = new TranslationController();
        $response = $controller->getAction();

        $this->assertEquals(200, $response->getCode());
        $this->assertEquals('OK', $response->getLabel());
        $this->assertTrue($response->getContent() instanceof Collection);

        $content = $response->getContent()->getItems();

        $this->assertCount(9, $content);
        $this->assertEquals('ASV', $content[0]['abbreviation']);
    }

    public function testGetActionReturnsOneTranslation()
    {
        $controller = new TranslationController();
        $controller->setRouteParams(array(
            'translationId' => 1
        ));

        $response = $controller->getAction();

        $this->assertEquals(200, $response->getCode());
        $this->assertEquals('OK', $response->getLabel());
        $this->assertTrue($response->getContent() instanceof TranslationEntity);

        $content = $response->getContent();
        $this->assertEquals('ASV', $content->getAbbreviation());
    }
}

