<?php
namespace Test\Integration\Controller;

use Controller\AuthenticateController;
use Controller\ListController;
use Controller\UserController;
use Domain\Mapper\UserMapper;
use Test\IntegrationTestCase;

/**
 * ListControllerTest
 *
 * @package \Test\Integration\Controller
 */
class ListControllerTest extends IntegrationTestCase
{
    public function setUp() : void
    {
        parent::setUp();

        $mapper = new UserMapper();

        $collection = $mapper->getCollection('users');
        $collection->deleteMany(array());

        $collection = $mapper->getCollection('lists');
        $collection->deleteMany(array());

        $collection = $mapper->getCollection('verses');
        $collection->deleteMany(array());
    }

    public function testCreateAction()
    {
        $this->_register();
        $this->_login();
        $this->_create('test');
    }

    public function testUpdateAction()
    {
        $this->_register();
        $this->_login();
        $id = $this->_create('test');

        $controller = new ListController();
        $controller->setRouteParams(array(
            'listId' => $id
        ));
        $controller->setJson(array(
            'name' => 'test updated'
        ));
        $response = $controller->updateAction();

        $this->assertEquals(200, $response->getCode());
        $this->assertEquals('OK', $response->getLabel());

        $content = $response->getContent();
        $this->assertEquals('test updated', $content['name']);
        $this->assertEquals($id, $content['id']);
    }

    public function testDeleteAction()
    {
        $this->_register();
        $this->_login();
        $id = $this->_create('test');

        $controller = new ListController();
        $controller->setRouteParams(array(
            'listId' => $id
        ));
        $response = $controller->deleteAction();

        $this->assertEquals(200, $response->getCode());
        $this->assertEquals('OK', $response->getLabel());

        $content = $response->getContent();
        $this->assertEquals(true, $content['success']);
    }

    public function testGetAllAction()
    {
        $this->_register();
        $this->_login();
        $a = $this->_create('test a');
        $b = $this->_create('test b');
        $c = $this->_create('test c');

        $controller = new ListController();
        $response = $controller->getAllAction();

        $this->assertEquals(200, $response->getCode());
        $this->assertEquals('OK', $response->getLabel());

        $content = $response->getContent();
        $this->assertCount(3, $content);

        $this->assertEquals($a, $content[0]['id']);
        $this->assertEquals($b, $content[1]['id']);
        $this->assertEquals($c, $content[2]['id']);
    }

    public function testGetOneAction()
    {
        $this->_register();
        $this->_login();
        $id = $this->_create('test');

        $controller = new ListController();
        $controller->setRouteParams(array(
            'listId' => $id
        ));
        $response = $controller->getOneAction();

        $this->assertEquals(200, $response->getCode());
        $this->assertEquals('OK', $response->getLabel());

        $content = $response->getContent();
        $this->assertEquals($id, $content['id']);
        $this->assertEquals('test', $content['name']);
    }

    public function testPutVerseAction()
    {
        $this->_register();
        $this->_login();
        $listId = $this->_create('test a');

        $this->_putVerse($listId, 1001001);
        $this->_putVerse($listId, 1001002);
        $this->_putVerse($listId, 1001003);
    }

    public function xtestGetAllVersesAction()
    {
        $this->_register();
        $this->_login();
        $listId = $this->_create('test a');

        $this->_putVerse($listId, 1001001);
        $this->_putVerse($listId, 1001002);
        $this->_putVerse($listId, 1001003);

        $controller = new ListController();
        $controller->setRouteParams(array(
            'listId' => $listId
        ));
        $response = $controller->getAllVersesAction();

        $this->assertEquals(200, $response->getCode());
        $this->assertEquals('OK', $response->getLabel());

        $content = $response->getContent();
        $this->assertCount(3, $content);
    }

    public function xtestDeleteVerseAction()
    {
        $this->_register();
        $this->_login();
        $listId = $this->_create('test a');

        $this->_putVerse($listId, 1001001);
        $this->_putVerse($listId, 1001002);
        $this->_putVerse($listId, 1001003);

        $controller = new ListController();
        $controller->setRouteParams(array(
            'listId' => $listId,
            'verseId' => 1001003
        ));
        $response = $controller->deleteVerseAction();

        $this->assertEquals(200, $response->getCode());
        $this->assertEquals('OK', $response->getLabel());

        /* Make sure there's only 2 now */
        $controller->setRouteParams(array(
            'listId' => $listId
        ));
        $response = $controller->getAllVersesAction();
        $this->assertEquals(200, $response->getCode());
        $this->assertEquals('OK', $response->getLabel());

        $content = $response->getContent();
        $this->assertCount(2, $content);
    }

    protected function _register()
    {
        $controller = new UserController();
        $controller->setJson(array(
            'email' => 'test@example.com',
            'password' => 'test123',
            'passwordConf' => 'test123'
        ));
        $response = $controller->registerAction();

        $this->assertEquals(200, $response->getCode());
        $this->assertEquals('OK', $response->getLabel());
    }

    protected function _login()
    {
        $controller = new AuthenticateController();
        $controller->setJson(array(
            'email' => 'test@example.com',
            'password' => 'test123'
        ));
        $response = $controller->loginAction();

        $this->assertEquals(200, $response->getCode());
        $this->assertEquals('OK', $response->getLabel());

        $this->assertArrayHasKey('token', $response->getContent());
    }

    protected function _logout()
    {
        $controller = new AuthenticateController();
        $response = $controller->logoutAction();

        $this->assertEquals(200, $response->getCode());
        $this->assertEquals('OK', $response->getLabel());

        $content = $response->getContent();

        $this->assertEquals(true, $content['success']);
    }

    protected function _create($name)
    {
        $controller = new ListController();
        $controller->setJson(array(
            'name' => $name
        ));
        $response = $controller->createAction();

        $this->assertEquals(200, $response->getCode());
        $this->assertEquals('OK', $response->getLabel());

        $content = $response->getContent();
        $this->assertEquals($name, $content['name']);
        $this->assertArrayHasKey('id', $content);

        return $content['id'];
    }

    protected function _putVerse($listId, $verseId)
    {
        $controller = new ListController();
        $controller->setRouteParams(array(
            'listId' => $listId,
            'verseId' => $verseId
        ));
        $response = $controller->putVerseAction();

        $this->assertEquals(200, $response->getCode());
        $this->assertEquals('OK', $response->getLabel());

        $content = $response->getContent();
        $this->assertTrue($content['success']);
    }
}
