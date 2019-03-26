<?php
namespace Controller;

use Core\Controller;
use Core\Http\HttpBadRequestException;
use Core\Http\Response;
use Domain\Mapper\RelationMapper;
use Domain\Service\TranslationFactory;

/**
 * Class RelationController
 *
 * @package Controller
 */
class RelationController extends Controller
{
    /**
     * @var \Domain\Mapper\RelationMapper
     */
    protected $_mapper;

    /**
     * @return \Core\Http\Response
     * @throws \Exception
     **/
    public function getAction()
    {
        if (!$this->_hasRouteParam('verseId')) {
            throw new HttpBadRequestException('Verse ID must be set.');
        }

        $collection = $this->getMapper()->findAll(array(
            'verseId' => $this->_getRouteParam('verseId')
        ));

        return new Response($collection, 200, 'OK');
    }

    /**
     * @return \Domain\Mapper\RelationMapper
     */
    public function getMapper()
    {
        if (null === $this->_mapper) {
            $this->setMapper(new RelationMapper());
        }

        $this->_mapper->setTable(
            TranslationFactory::getTranslationTable($this->_hasParam('translation') ? $this->_getParam('translation') : null)
        );

        return $this->_mapper;
    }

    /**
     * @param \Domain\Mapper\RelationMapper $mapper
     * @return $this
     */
    public function setMapper(RelationMapper $mapper)
    {
        $this->_mapper = $mapper;

        return $this;
    }
}
