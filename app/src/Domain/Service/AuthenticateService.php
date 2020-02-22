<?php
namespace Domain\Service;
use Core\Domain\Service\SessionService;
use Core\Http\HttpBadRequestException;
use Core\Http\HttpUnauthorizedException;
use Domain\Mapper\UserMapper;
use MongoDB\BSON\ObjectId;

/**
 * Class AuthenticateService
 *
 * @package Domain\Service
 */
class AuthenticateService
{
    /**
     * @var \Domain\Mapper\UserMapper
     */
    protected $_userMapper;

    /**
     * @var \Core\Domain\Service\SessionService
     */
    protected $_sessionService;

    /**
     * Authenticates the user
     *
     * @param array $data
     * @return array
     * @throws \Core\Http\HttpBadRequestException
     * @throws \Exception
     */
    public function login($data = array())
    {
        $errors = array();

        if (!isset($data['email'])) {
            $errors['email'] = 'Email is required.';
        }

        if (!isset($data['password'])) {
            $errors['password'] = 'Password is required.';
        }

        if (count($errors) > 0) {
            $exception = new HttpBadRequestException();
            $exception->setDescription('Invalid request');
            $exception->setErrors($errors);

            $this->getSessionService()->destroy();

            throw $exception;
        }

        $user = $this->getUserMapper()->findOne(array(
            'email' => filter_var($data['email'], FILTER_SANITIZE_EMAIL)
        ));

        if (!$user) {
            $exception = new HttpBadRequestException();
            $exception->setDescription('Bad request');
            $exception->setErrors(array(
                'password' => 'Invalid credentials.'
            ));

            $this->getSessionService()->destroy();

            throw $exception;
        }

        if (!password_verify($data['password'], $user['password'])) {
            $exception = new HttpBadRequestException();
            $exception->setDescription('Bad request');
            $exception->setErrors(array(
                'password' => 'Invalid credentials.'
            ));

            $this->getSessionService()->destroy();

            throw $exception;
        }

        $this->getSessionService()->set('id', $user['id']);

        return array(
            'token' => $this->getSessionService()->getId()
        );
    }

    /**
     * Logs the user out
     *
     * @return bool
     * @throws \Exception
     */
    public function logout()
    {
        $this->getSessionService()->destroy();

        return true;
    }

    /**
     * Gets the current logged in user information
     *
     * @return array|bool
     * @throws \Core\Http\HttpUnauthorizedException
     * @throws \Exception
     */
    public function me()
    {
        if (!$this->getSessionService()->has('id')) {
            $exception = new HttpUnauthorizedException();
            $exception->setDescription('Unauthorized');

            throw $exception;
        }

        $user = $this->getUserMapper()->findOne(array(
            '_id' => new ObjectId($this->getSessionService()->get('id'))
        ));

        if (!$user) {
            $exception = new HttpUnauthorizedException();
            $exception->setDescription('Unauthorized');

            throw $exception;
        }

        unset($user['password']);

        return $user;
    }

    /**
     * @return \Domain\Mapper\UserMapper
     * @throws \Exception
     */
    public function getUserMapper()
    {
        if ($this->_userMapper == null) {
            $this->_userMapper = new UserMapper();
        }

        return $this->_userMapper;
    }

    /**
     * @param \Domain\Mapper\UserMapper $userMapper
     * @return $this
     */
    public function setUserMapper($userMapper)
    {
        $this->_userMapper = $userMapper;

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
