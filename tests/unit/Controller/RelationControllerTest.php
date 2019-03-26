<?php
namespace Test\Unit\Controller;

use Controller\RelationController;
use PHPUnit\Framework\TestCase;

/**
 * RelationControllerTest
 */
class RelationControllerTest extends TestCase
{
    public function testChaptersActionThrowsHttpBadRequest()
    {
        $this->expectException('\Core\Http\HttpBadRequestException');
        $this->expectExceptionMessage('Verse ID must be set.');

        $controller = new RelationController();
        $controller->getAction();
    }
}

