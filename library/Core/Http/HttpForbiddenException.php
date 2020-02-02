<?php
namespace Core\Http;

/**
 * HttpForbiddenException
 *
 * @package Core\Http
 * @copyright Rob Keplin 2020
 */
class HttpForbiddenException extends HttpException
{
    /**
     * @var int
     */
    protected $_httpCode = 403;

    /**
     * @var string
     */
    protected $_message = 'Forbidden';

    /**
     * @var bool
     */
    protected $_logException = true;
}
