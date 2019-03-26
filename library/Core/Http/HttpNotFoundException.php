<?php
namespace Core\Http;

/**
 * HttpNotFoundException
 *
 * @package Core\Http
 * @copyright Rob Keplin 2018
 */
class HttpNotFoundException extends HttpException
{
    /**
     * @var int
     */
    protected $_httpCode = 404;

    /**
     * @var string
     */
    protected $_message = 'Not Found';

    /**
     * We don't need to log this type of exception
     *
     * @var bool
     */
    protected $_logException = false;
}
