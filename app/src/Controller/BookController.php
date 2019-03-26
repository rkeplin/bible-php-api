<?php
namespace Controller;

use Core\Controller;
use Core\Http\HttpBadRequestException;
use Core\Http\Response;
use Domain\Mapper\BookMapper;
use Exception;

/**
 * Class BookController
 *
 * @package Controller
 */
class BookController extends Controller
{
    /**
     * @var \Domain\Mapper\BookMapper
     */
    protected $_mapper;

    /**
     * @return \Core\Http\Response
     **/
    public function getAction()
    {
        if ($this->_hasRouteParam('bookId')) {
            $item = $this->getMapper()->findOneById($this->_getRouteParam('bookId'));

            return new Response($item, 200, 'OK');
        }

        $collection = $this->getMapper()->findAll();

        return new Response($collection, 200, 'OK');
    }

    /**
     * @return \Core\Http\Response
     * @throws Exception
     */
    public function chaptersAction()
    {
        if (!$this->_hasRouteParam('bookId')) {
            throw new HttpBadRequestException('Book ID must be set.');
        }

        $collection = $this->getMapper()->findChapters($this->_getRouteParam('bookId'));

        return new Response($collection, 200, 'OK');
    }

    /**
     * @return \Domain\Mapper\BookMapper
     */
    public function getMapper()
    {
        if (null === $this->_mapper) {
            $this->setMapper(new BookMapper());
        }

        return $this->_mapper;
    }

    /**
     * @param \Domain\Mapper\BookMapper $mapper
     * @return $this
     */
    public function setMapper(BookMapper $mapper)
    {
        $this->_mapper = $mapper;

        return $this;
    }
}
