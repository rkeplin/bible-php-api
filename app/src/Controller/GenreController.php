<?php
namespace Controller;

use Core\Controller;
use Core\Http\Response;
use Domain\Mapper\GenreMapper;

/**
 * Class GenreController
 *
 * @package Controller
 */
class GenreController extends Controller
{
    /**
     * @var \Domain\Mapper\GenreMapper
     */
    protected $_mapper;

    /**
     * @return \Core\Http\Response
     **/
    public function getAction()
    {
        if ($this->_hasRouteParam('genreId')) {
            $item = $this->getMapper()->findOneById($this->_getRouteParam('genreId'));

            return new Response($item, 200, 'OK');
        }

        $collection = $this->getMapper()->findAll();

        return new Response($collection, 200, 'OK');
    }

    /**
     * @return \Domain\Mapper\GenreMapper
     */
    public function getMapper()
    {
        if (null === $this->_mapper) {
            $this->setMapper(new GenreMapper());
        }

        return $this->_mapper;
    }

    /**
     * @param \Domain\Mapper\GenreMapper $mapper
     * @return $this
     */
    public function setMapper(GenreMapper $mapper)
    {
        $this->_mapper = $mapper;

        return $this;
    }
}
