<?php
namespace Core\Http;

/**
 * HttpUnauthorizedException
 *
 * @package Core\Http
 * @copyright Rob Keplin 2020
 */
class HttpUnauthorizedException extends HttpException
{
    /**
     * @var int
     */
    protected $_httpCode = 401;

    /**
     * @var string
     */
    protected $_message = 'Unauthorized';

    /**
     * @var bool
     */
    protected $_logException = true;
}
