<?php
namespace Core\Http;

/**
 * HttpInternalErrorException
 *
 * @package Core\Http
 * @copyright Rob Keplin 2018
 */
class HttpInternalErrorException extends HttpException
{
    /**
     * @var int
     */
    protected $_httpCode = 500;

    /**
     * @var string
     */
    protected $_message = 'Internal Error';

    /**
     * We need to log this type of exception
     *
     * @var bool
     */
    protected $_logException = true;
}
