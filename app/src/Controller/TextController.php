<?php
namespace Controller;

use Core\Controller;
use Core\Http\HttpBadRequestException;
use Core\Http\HttpNotFoundException;
use Core\Http\Response;
use Domain\Mapper\TextMapper;
use Domain\Service\TranslationFactory;

/**
 * Class TextController
 *
 * @package Controller
 */
class TextController extends Controller
{
    /**
     * @var \Domain\Mapper\TextMapper
     */
    protected $_mapper;

    /**
     * @return \Core\Http\Response
     * @throws \Exception
     **/
    public function getAction()
    {
        if ($this->_hasRouteParam('verseId')) {
            $item = $this->getMapper()->findOneById($this->_getRouteParam('verseId'));

            if (!$item) {
                throw new HttpNotFoundException('Not Found');
            }

            return new Response($item, 200, 'OK');
        }

        if (!$this->_hasRouteParam('bookId')) {
            throw new HttpBadRequestException('Book ID is required.');
        }

        if (!$this->_hasRouteParam('chapterId')) {
            throw new HttpBadRequestException('Chapter ID is required.');
        }

        $options = array(
            'bookId' => $this->_getRouteParam('bookId'),
            'chapterId' => $this->_getRouteParam('chapterId'),
        );

        $collection = $this->getMapper()->findAll($options);

        return new Response($collection, 200, 'OK');
    }

    /**
     * @return \Domain\Mapper\TextMapper
     */
    public function getMapper()
    {
        if (null === $this->_mapper) {
            $this->setMapper(new TextMapper());
        }

        $this->_mapper->setTable(
            TranslationFactory::getTranslationTable($this->_hasParam('translation') ? $this->_getParam('translation') : null)
        );

        return $this->_mapper;
    }

    /**
     * @param \Domain\Mapper\TextMapper $mapper
     * @return $this
     */
    public function setMapper(TextMapper $mapper)
    {
        $this->_mapper = $mapper;

        return $this;
    }
}
