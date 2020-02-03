<?php
namespace Controller;

use Core\Controller;
use Core\Domain\Service\SessionService;
use Core\Http\Response;
use Domain\Service\AuthenticateService;

/**
 * Class AuthenticateController
 *
 * @package Controller
 */
class AuthenticateController extends Controller
{
    /**
     * @var \Domain\Service\AuthenticateService
     */
    protected $_service;

    /**
     * @var \Core\Domain\Service\SessionService
     */
    protected $_sessionService;

    /**
     * AuthenticateController constructor.
     */
    public function __construct()
    {
        $this->getSessionService()->start();
    }

    /**
     * @return \Core\Http\Response
     * @throws \Exception
     **/
    public function loginAction()
    {
        $data = $this->_getJson();

        $response = $this->getService()->login($data);

        return new Response($response, 200, 'OK');
    }

    /**
     * @return \Core\Http\Response
     * @throws \Exception
     **/
    public function logoutAction()
    {
        $this->getService()->logout();

        return new Response(array('success' => true), 200, 'OK');
    }

    /**
     * @return \Core\Http\Response
     * @throws \Exception
     **/
    public function meAction()
    {
        $response = $this->getService()->me();

        return new Response($response, 200, 'OK');
    }

    /**
     * @return \Domain\Service\AuthenticateService
     * @throws \Exception
     */
    public function getService()
    {
        if ($this->_service == null) {
            $this->setService(new AuthenticateService());
        }

        return $this->_service;
    }

    /**
     * @param \Domain\Service\AuthenticateService $service
     * @return $this
     */
    public function setService(AuthenticateService $service)
    {
        $this->_service = $service;

        return $this;
    }

    /**
     * @return \Core\Domain\Service\SessionService
     */
    public function getSessionService()
    {
        if ($this->_sessionService == null) {
            $this->_sessionService = new SessionService();
        }

        return $this->_sessionService;
    }

    /**
     * @param \Core\Domain\Service\SessionService $sessionService
     * @return $this;
     */
    public function setSessionService($sessionService)
    {
        $this->_sessionService = $sessionService;

        return $this;
    }
}
