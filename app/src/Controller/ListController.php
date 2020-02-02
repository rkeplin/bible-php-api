<?php
namespace Controller;

use Core\Controller;
use Core\Domain\Service\SessionService;
use Core\Http\Response;
use Domain\Mapper\ListMapper;
use Domain\Service\AuthenticateService;
use MongoDB\BSON\ObjectId;

/**
 * Class ListController
 *
 * @package Controller
 */
class ListController extends Controller
{
    /**
     * @var \Domain\Service\AuthenticateService
     */
    protected $_authService;

    /**
     * @var \Core\Domain\Service\SessionService
     */
    protected $_sessionService;

    /**
     * @var \Domain\Mapper\ListMapper
     */
    protected $_mapper;

    /**
     * ListController constructor.
     */
    public function __construct()
    {
        $this->getSessionService()->start();
    }

    /**
     * @return \Core\Http\Response
     * @throws \Exception
     **/
    public function getAllAction()
    {
        $user = $this->getAuthService()->me();

        $response = $this->getMapper()->findAll(array(
            'user._id' => new ObjectId($user['id']),
        ));

        return new Response($response, 200, 'OK');
    }

    /**
     * @return \Core\Http\Response
     * @throws \Exception
     **/
    public function getOneAction()
    {
        $user = $this->getAuthService()->me();

        $id = $this->_getRouteParam('listId');

        $response = $this->getMapper()->findOne(array(
            'user._id' => new ObjectId($user['id']),
            '_id' => new ObjectId($id)
        ));

        if (!$response) {
            return new Response(array('success' => false), 404, 'Not Found');
        }

        return new Response($response, 200, 'OK');
    }

    /**
     * @return \Core\Http\Response
     * @throws \Exception
     **/
    public function createAction()
    {
        $user = $this->getAuthService()->me();

        $data = $this->_getJson();

        $response = $this->getMapper()->create($user['id'], $data);

        return new Response($response, 200, 'OK');
    }

    /**
     * @return \Core\Http\Response
     * @throws \Exception
     **/
    public function updateAction()
    {
        $user = $this->getAuthService()->me();

        $data = $this->_getJson();

        $id = $this->_getRouteParam('listId');

        $response = $this->getMapper()->update($user['id'], $id, $data);

        return new Response($response, 200, 'OK');
    }

    /**
     * @return \Core\Http\Response
     * @throws \Exception
     **/
    public function deleteAction()
    {
        $user = $this->getAuthService()->me();

        $id = $this->_getRouteParam('listId');

        $this->getMapper()->delete($user['id'], $id);

        return new Response(array('success' => true), 200, 'OK');
    }

    /**
     * @return Response
     * @throws \Core\Http\HttpUnauthorizedException
     * @throws \Exception
     */
    public function putVerseAction()
    {
        $user = $this->getAuthService()->me();

        $id = $this->_getRouteParam('listId');
        $verseId = $this->_getRouteParam('verseId');
        $translation = $this->_hasParam('translation') ? $this->_getParam('translation') : null;

        $response = $this->getMapper()->putVerse($user['id'], $id, $verseId, $translation);

        if (!$response) {
            return new Response(array('success' => false), 400, 'Bad Request');
        }

        return new Response(array('success' => true), 200, 'OK');
    }

    /**
     * @return Response
     * @throws \Core\Http\HttpBadRequestException
     * @throws \Core\Http\HttpNotFoundException
     * @throws \Core\Http\HttpUnauthorizedException
     * @throws \Exception
     */
    public function getAllVersesAction()
    {
        $user = $this->getAuthService()->me();

        $id = $this->_getRouteParam('listId');
        $response = $this->getMapper()->findAllVerses($user['id'], $id);

        return new Response($response, 200, 'OK');
    }

    /**
     * @return Response
     * @throws \Core\Http\HttpUnauthorizedException
     * @throws \Exception
     */
    public function deleteVerseAction()
    {
        $user = $this->getAuthService()->me();

        $id = $this->_getRouteParam('listId');
        $verseId = $this->_getRouteParam('verseId');
        $translation = $this->_hasParam('translation') ? $this->_getParam('translation') : null;

        $this->getMapper()->deleteVerse($user['id'], $id, $verseId, $translation);

        return new Response(array('success' => true), 200, 'OK');
    }

    /**
     * @return \Domain\Mapper\ListMapper
     * @throws \Exception
     */
    public function getMapper()
    {
        if ($this->_mapper == null) {
            $this->_mapper = new ListMapper();
        }

        return $this->_mapper;
    }

    /**
     * @param \Domain\Mapper\ListMapper $mapper
     * @return $this
     */
    public function setMapper(ListMapper $mapper)
    {
        $this->_mapper = $mapper;

        return $this;
    }

    /**
     * @return \Domain\Service\AuthenticateService
     * @throws \Exception
     */
    public function getAuthService()
    {
        if ($this->_authService == null) {
            $this->setAuthService(new AuthenticateService());
        }

        return $this->_authService;
    }

    /**
     * @param \Domain\Service\AuthenticateService $authService
     * @return $this
     */
    public function setAuthService(AuthenticateService $authService)
    {
        $this->_authService = $authService;

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
