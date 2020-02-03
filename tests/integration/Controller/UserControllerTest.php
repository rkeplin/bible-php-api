<?php
namespace Test\Integration\Controller;

use Controller\UserController;
use Domain\Mapper\UserMapper;
use Test\IntegrationTestCase;

/**
 * UserControllerTest
 *
 * @package \Test\Integration\Controller
 */
class UserControllerTest extends IntegrationTestCase
{
    public function setUp() : void
    {
        parent::setUp();

        $mapper = new UserMapper();

        $collection = $mapper->getCollection('users');
        $collection->deleteMany(array());
    }

    public function testRegisterAction()
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

        $content = $response->getContent();

        $this->assertEquals('test@example.com', $content['email']);
    }
}

