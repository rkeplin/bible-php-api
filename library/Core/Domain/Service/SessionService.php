<?php
namespace Core\Domain\Service;

use Exception;

/**
 * Core MVC Framework.
 *
 * @copyright Copyright (c) 2020 Rob Keplin.
 * @license TBD
 **/
class SessionService
{
    /**
     * @var string
     **/
    protected static $_defaultSessionName;

    /**
     * @var bool
     */
    protected static $_started = false;

    /**
     * @var string
     **/
    protected $_sessionName;

    /**
     * @param string $sessionName
     **/
    public function __construct($sessionName = null)
    {
        $this->_sessionName = 'PHPSESSID';

        if (self::$_defaultSessionName != null) {
            $this->_sessionName = self::$_defaultSessionName;
        }

        if($sessionName != null) {
            $this->_sessionName = $sessionName;
        }
    }

    /**
     * Starts a session, if not already started
     */
    public function start()
    {
        if (getenv('ENVIRONMENT') == 'testing') {
            return;
        }

        if (self::$_started) {
            return;
        }

        session_name($this->getSessionName());
        session_start();

        self::$_started = true;
    }

    /**
     * Gets a session variable
     *
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function get($id)
    {
        return $_SESSION[$id];
    }

    /**
     * Returns whether a session variable is set
     *
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function has($id)
    {
        return isset($_SESSION[$id]);
    }

    /**
     * Sets a session variable
     *
     * @param $id
     * @param $value
     * @throws \Exception
     */
    public function set($id, $value)
    {
        $_SESSION[$id] = $value;
    }

    /**
     * Destroys session
     *
     * @throws \Exception
     */
    public function destroy()
    {
        if (getenv('ENVIRONMENT') == 'testing') {
            $_SESSION = array();

            return;
        }

        if (!$this->isActive()) {
            throw new Exception('Session is not active.  Cannot be destroyed.');
        }

        $_SESSION = array();

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        session_destroy();
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getId()
    {
        if (getenv('ENVIRONMENT') == 'testing') {
            return 123;
        }

        if (!$this->isActive()) {
            throw new Exception('Session is not active.  Cannot retrieve ID.');
        }

        return session_id();
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return self::$_started;
    }

    /**
     * @return string
     */
    public function getSessionName()
    {
        return $this->_sessionName;
    }

    /**
     * Sets the default PDO instance.
     *
     * @param string $sessionName
     **/
    public static function setDefaultSessionName($sessionName)
    {
        self::$_defaultSessionName = $sessionName;
    }
}
