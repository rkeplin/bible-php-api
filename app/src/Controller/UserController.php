<?php
namespace Controller;

use Core\Controller;
use Core\Http\Response;
use Domain\Mapper\UserMapper;

/**
 * Class UserController
 *
 * @package Controller
 */
class UserController extends Controller
{
    /**
     * @var \Domain\Mapper\UserMapper
     */
    protected $_mapper;

    /**
     * @return \Core\Http\Response
     * @throws \Core\Http\HttpBadRequestException
     * @throws \Exception
     */
    public function registerAction()
    {
        $data = $this->_getJson();

        $response = $this->getMapper()->register($data);

        return new Response($response, 200, 'OK');
    }

    /**
     * @return \Domain\Mapper\UserMapper
     * @throws \Exception
     */
    public function getMapper()
    {
        if (null === $this->_mapper) {
            $this->setMapper(new UserMapper());
        }

        return $this->_mapper;
    }

    /**
     * @param \Domain\Mapper\UserMapper $mapper
     * @return $this
     */
    public function setMapper(UserMapper $mapper)
    {
        $this->_mapper = $mapper;
    }
}
