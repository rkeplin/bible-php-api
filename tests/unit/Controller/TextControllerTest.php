<?php
namespace Test\Unit\Controller;

use Controller\TextController;
use PHPUnit\Framework\TestCase;

/**
 * TextControllerTest
 */
class TextControllerTest extends TestCase
{
    public function testGetActionThrowsHttpNotFoundException()
    {
        $this->expectException('\Core\Http\HttpNotFoundException');
        $this->expectExceptionMessage('Not Found');

        $mapper = $this->getMockBuilder('\Domain\Mapper\TextMapper')
            ->disableOriginalConstructor()
            ->setMethods(array('setTable', 'findOneById'))
            ->getMock();

        $mapper->expects($this->once())
            ->method('setTable');

        $mapper->expects($this->once())
            ->method('findOneById')
            ->with(123)
            ->willReturn(false);

        $controller = new TextController();
        $controller->setMapper($mapper);
        $controller->setRouteParams(array(
            'verseId' => 123
        ));
        $controller->getAction();
    }

    public function testGetActionThrowsHttpBadRequestExceptionIfBookIdNotSet()
    {
        $this->expectException('\Core\Http\HttpBadRequestException');
        $this->expectExceptionMessage('Book ID is required.');

        $controller = new TextController();
        $controller->getAction();
    }

    public function testGetActionThrowsHttpBadRequestExceptionIfChapterIdNotSet()
    {
        $this->expectException('\Core\Http\HttpBadRequestException');
        $this->expectExceptionMessage('Chapter ID is required.');

        $controller = new TextController();
        $controller->setRouteParams(array(
            'bookId' => 1
        ));

        $controller->getAction();
    }
}

