<?php
namespace Core\Http;

/**
 * HttpBadRequestException
 *
 * @package Core\Http
 * @copyright Rob Keplin 2018
 */
class HttpBadRequestException extends HttpException
{
    /**
     * @var int
     */
    protected $_httpCode = 400;

    /**
     * @var string
     */
    protected $_message = 'Bad Request';

    /**
     * We don't need to log this type of exception
     *
     * @var bool
     */
    protected $_logException = false;
}
