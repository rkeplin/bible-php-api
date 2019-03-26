<?php
namespace Domain\Mapper;

use Core\Domain\Entity\Collection;
use Core\Domain\Mapper\AbstractMapper;
use Domain\Entity\BookEntity;
use Domain\Entity\GenreEntity;
use Domain\Service\TranslationFactory;

/**
 * Class BookMapper
 *
 * @package Domain\Mapper
 */
class BookMapper extends AbstractMapper
{
    /**
     * Model table
     *
     * @var string
     */
    protected $_table = 'books';

    /**
     * Finds a bible translation by ID
     *
     * @param int $id
     * @return \Domain\Entity\BookEntity
     */
    public function findOneById($id)
    {
        $sql = $this->_baseSql() . 'WHERE b.id = :id';

        $stmt = $this->_db->prepare($sql);
        $stmt->execute(array(
            'id' => $id
        ));

        $row = $stmt->fetch();

        return $this->_getEntityFromRow($row);
    }

    /**
     * Finds bible translations
     *
     * @param array $options
     * @return \Core\Domain\Entity\Collection
     **/
    public function findAll($options = array())
    {
        $sql = $this->_baseSql() . 'ORDER BY b.id ASC';

        $stmt = $this->_db->prepare($sql);
        $stmt->execute();

        $rows = $stmt->fetchAll();

        $collection = new Collection();

        foreach($rows as $row) {
            $entity = $this->_getEntityFromRow($row);

            $collection->addItem($entity);
        }

        return $collection;
    }

    /**
     * Finds all the chapters from a book.
     *
     * @param $id
     * @return array
     */
    public function findChapters($id)
    {
        $sql = sprintf(
            'SELECT DISTINCT(chapterId) AS id FROM %s WHERE bookId = :bookId',
            TranslationFactory::getTranslationTable()
        );

        $stmt = $this->_db->prepare($sql);
        $stmt->execute(array(
            'bookId' => $id
        ));

        $rows = $stmt->fetchAll();

        foreach ($rows as $row) {
            $row->id = (int) $row->id;
        }

        return $rows;
    }

    /**
     * @param $row
     * @return BookEntity
     */
    protected function _getEntityFromRow($row)
    {
        $genreEntity = new GenreEntity();
        $genreEntity->setId($row->genreId);
        $genreEntity->setName($row->genreName);

        $bookEntity = new BookEntity();
        $bookEntity->setGenre($genreEntity);
        $bookEntity->populate($row);

        return $bookEntity;
    }

    /**
     * @return string
     */
    protected function _baseSql() {
        $sql = 'SELECT b.*, g.id as genreId, g.name as genreName '
             . 'FROM books b '
             . 'INNER JOIN genres g ON b.genreId  = g.id ';

        return $sql;
    }
}
