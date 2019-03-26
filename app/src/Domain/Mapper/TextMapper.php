<?php
namespace Domain\Mapper;

use Core\Domain\Entity\Collection;
use Core\Domain\InvalidParameterException;
use Core\Domain\Mapper\AbstractMapper;
use Core\Http\HttpNotFoundException;
use Domain\Entity\BookEntity;
use Domain\Entity\TextEntity;
use Domain\Service\TranslationFactory;

/**
 * Class TextMapper
 *
 * @package Domain\Mapper
 */
class TextMapper extends AbstractMapper
{
    /**
     * Finds a bible translation by ID
     *
     * @param int $id
     * @return \Domain\Entity\TextEntity|false
     */
    public function findOneById($id)
    {
        $sql = $this->_baseSql()
             . 'WHERE t.id = :id';

        $stmt = $this->_db->prepare($sql);
        $stmt->execute(array(
            'id' => $id
        ));

        $row = $stmt->fetch();

        if (!$row) {
            return false;
        }

        return $this->_getEntityFromRow($row);
    }

    /**
     * Finds bible translations
     *
     * @param array $options
     * @return \Core\Domain\Entity\Collection
     * @throws \Core\Domain\InvalidParameterException
     **/
    public function findAll($options = array())
    {
        if (!isset($options['bookId'])) {
            throw new InvalidParameterException('Book ID is required.');
        }

        if (!isset($options['chapterId'])) {
            throw new InvalidParameterException('Chapter ID is required.');
        }

        $sql = $this->_baseSql()
             . 'WHERE bookId = :bookId AND chapterId = :chapterId ORDER BY t.id ASC';

        $stmt = $this->_db->prepare($sql);
        $stmt->execute(array(
            'bookId' => $options['bookId'],
            'chapterId' => $options['chapterId']
        ));

        $rows = $stmt->fetchAll();

        $collection = new Collection();

        foreach($rows as $row) {
            $entity = $this->_getEntityFromRow($row);

            $collection->addItem($entity);
        }

        return $collection;
    }

    /**
     * @param $row
     * @return \Domain\Entity\TextEntity
     */
    protected function _getEntityFromRow($row)
    {
        $bookEntity = new BookEntity();
        $bookEntity->setId($row->bookId);
        $bookEntity->setName($row->bookName);
        $bookEntity->setTestament($row->bookTestament);

        $textEntity = new TextEntity();
        $textEntity->setBook($bookEntity);
        $textEntity->populate($row);

        return $textEntity;
    }

    /**
     * @return string
     */
    protected function _baseSql() {
        $sql = 'SELECT t.*, b.id AS bookId, b.name AS bookName, b.testament AS bookTestament '
             . sprintf('FROM %s t ', $this->getTable())
             . 'INNER JOIN books b '
             . 'ON t.bookId = b.id ';

        return $sql;
    }

    /**
     * @return string
     */
    public function getTable()
    {
        $table = parent::getTable();

        if (!$table) {
            $table = TranslationFactory::getTranslationTable();
        }

        return $table;
    }
}
