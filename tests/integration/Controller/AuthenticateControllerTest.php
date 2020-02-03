<?php
namespace Test\Integration\Controller;

use Controller\AuthenticateController;
use Controller\UserController;
use Domain\Mapper\UserMapper;
use Test\IntegrationTestCase;

/**
 * AuthenticateControllerTest
 *
 * @package \Test\Integration\Controller
 */
class AuthenticateControllerTest extends IntegrationTestCase
{
    public function setUp() : void
    {
        parent::setUp();

        $mapper = new UserMapper();

        $collection = $mapper->getCollection('users');
        $collection->deleteMany(array());
    }

    /**
     * @runInSeparateProcess
     */
    public function testLoginAction()
    {
        $this->_register();
        $this->_login();
    }

    /**
     * @runInSeparateProcess
     */
    public function testLogoutAction()
    {
        $this->_register();
        $this->_login();
        $this->_logout();
    }

    /**
     * @runInSeparateProcess
     */
    public function testMeAction()
    {
        $this->_register();
        $this->_login();

        $controller = new AuthenticateController();
        $response = $controller->meAction();

        $this->assertEquals(200, $response->getCode());
        $this->assertEquals('OK', $response->getLabel());

        $content = $response->getContent();

        $this->assertEquals('test@example.com', $content['email']);
        $this->assertArrayHasKey('dateRegistered', $content);
        $this->assertArrayNotHasKey('password', $content);
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
}
