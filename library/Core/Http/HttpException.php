<?php
namespace Core\Http;

use Exception;
use Throwable;

/**
 * HttpException
 *
 * @package Core\Http
 * @copyright Rob Keplin 2018
 */
abstract class HttpException extends Exception
{
    /**
     * @var int
     */
    protected $_httpCode = 404;

    /**
     * @var string
     */
    protected $_message;

    /**
     * @var string
     */
    protected $_description;

    /**
     * @var array
     */
    protected $_errors;

    /**
     * @var string
     */
    protected $_debugMessage;

    /**
     * @var bool
     */
    protected $_logException = true;

    /**
     * HttpBadRequestException constructor.
     *
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "", $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->setDescription($message);
    }

    /**
     * @param $httpCode
     */
    public function setHttpCode($httpCode)
    {
        $this->_httpCode = $httpCode;
    }

    /**
     * @return int
     */
    public function getHttpCode()
    {
        return $this->_httpCode;
    }

    /**
     * @param $message
     */
    public function setTitleMessage($message)
    {
        $this->_message = $message;
    }

    /**
     * @return string
     */
    public function getTitleMessage()
    {
        return $this->_message;
    }

    /**
     * @param $description
     */
    public function setDescription($description)
    {
        $this->_description = $description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->_description;
    }

    /**
     * @param $error
     */
    public function addError($error)
    {
        $this->_errors[] = $error;
    }

    /**
     * @param array $errors
     */
    public function setErrors($errors = array())
    {
        if (count($errors) == 0) {
            return;
        }

        foreach ($errors as $error) {
            $this->addError($error);
        }
    }

    /**
     * @return array
     */
    public function getErrors() {
        return $this->_errors;
    }

    /**
     * Gets the debug message
     *
     * @return array
     */
    public function getDebugMessage()
    {
        $message = array();
        $i = 0;
        $e = $this;

        while ($e = $e->getPrevious()) {
            $message[] = array(
                'class' => get_class($e),
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            );

            $i++;

            if ($i == 5) {
                break;
            }
        }

        return $message;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $error = array(
            'httpCode' => $this->getHttpCode(),
            'message' => $this->getTitleMessage(),
            'description' => $this->getDescription(),
            'errors' => $this->getErrors(),
            'debug' => $this->getDebugMessage()
        );

        if ($this->_logException) {
            error_log(json_encode($error));
        }

        return $error;
    }
}
