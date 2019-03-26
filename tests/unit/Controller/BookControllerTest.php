<?php
namespace Test\Unit\Controller;

use Controller\BookController;
use PHPUnit\Framework\TestCase;

/**
 * BookControllerTest
 */
class BookControllerTest extends TestCase
{
    public function testChaptersActionThrowsHttpBadRequest()
    {
        $this->expectException('\Core\Http\HttpBadRequestException');
        $this->expectExceptionMessage('Book ID must be set.');

        $controller = new BookController();
        $controller->chaptersAction();
    }
}

