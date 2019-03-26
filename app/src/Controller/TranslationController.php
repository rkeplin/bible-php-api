<?php
namespace Controller;

use Core\Controller;
use Core\Http\Response;
use Domain\Mapper\TranslationMapper;

/**
 * Class TranslationController
 *
 * @package Controller
 */
class TranslationController extends Controller
{
    /**
     * @var \Domain\Mapper\TranslationMapper
     */
    protected $_mapper;

    /**
     * @return \Core\Http\Response
     **/
    public function getAction()
    {
        if ($this->_hasRouteParam('translationId')) {
            $item = $this->getMapper()->findOneById($this->_getRouteParam('translationId'));

            return new Response($item, 200, 'OK');
        }

        $collection = $this->getMapper()->findAll();

        return new Response($collection, 200, 'OK');
    }

    /**
     * @return \Domain\Mapper\TranslationMapper
     */
    public function getMapper()
    {
        if (null === $this->_mapper) {
            $this->setMapper(new TranslationMapper());
        }

        return $this->_mapper;
    }

    /**
     * @param \Domain\Mapper\TranslationMapper $mapper
     * @return $this
     */
    public function setMapper(TranslationMapper $mapper)
    {
        $this->_mapper = $mapper;

        return $this;
    }
}
