<?php
namespace Controller;

use Core\Controller;
use Core\Http\Response;
use Domain\Mapper\RegisterMapper;

/**
 * Class RegisterController
 *
 * @package Controller
 */
class RegisterController extends Controller
{
    /**
     * @var \Domain\Mapper\RegisterMapper
     */
    protected $_mapper;

    /**
     * @return \Core\Http\Response
     * @throws \Core\Http\HttpBadRequestException
     * @throws \Exception
     */
    public function postAction()
    {
        $data = $this->_getJson();

        $response = $this->getMapper()->register($data);

        return new Response($response, 200, 'OK');
    }

    /**
     * @return \Domain\Mapper\RegisterMapper
     * @throws \Exception
     */
    public function getMapper()
    {
        if (null === $this->_mapper) {
            $this->setMapper(new RegisterMapper());
        }

        return $this->_mapper;
    }

    /**
     * @param \Domain\Mapper\RegisterMapper $mapper
     * @return $this
     */
    public function setMapper(RegisterMapper $mapper)
    {
        $this->_mapper = $mapper;
    }
}
