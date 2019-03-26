<?php
namespace Domain\Mapper;

use Core\Domain\Mapper\AbstractMapper;
use Core\Domain\InvalidParameterException;
use Domain\Entity\BookEntity;
use Domain\Entity\RelationEntity;
use Domain\Service\TranslationFactory;

/**
 * Class RelationMapper
 *
 * @package Domain\Mapper
 */
class RelationMapper extends AbstractMapper
{
    /**
     * Finds related bible translations
     *
     * @param array $options
     * @return array
     * @throws \Core\Domain\InvalidParameterException
     **/
    public function findAll($options = array())
    {
        if (!isset($options['verseId'])) {
            throw new InvalidParameterException('Verse ID is required.');
        }

        $rows = $this->_fetchAllRelations($options);

        $collection = array();

        foreach($rows as $row) {
            if ($row->endVerse == '00000000') {
                $entity = $this->_populateEntityFromRow($row);

                $collection[] = array($entity->toArray());

                continue;
            }

            $groupedRows = $this->_fetchGroupedRelations($row->startVerse, $row->endVerse);

            $item = array();

            foreach ($groupedRows as $groupedRow) {
                $entity = $this->_populateEntityFromRow($groupedRow);
                $entity->setGroup($row->startVerse);

                $item[] = $entity->toArray();
            }

            $collection[] = $item;
        }

        return $collection;
    }

    /**
     * @param array $options
     * @return array
     * @throws \Core\Domain\InvalidParameterException
     */
    protected function _fetchAllRelations($options = array())
    {
        if (!isset($options['verseId'])) {
            throw new InvalidParameterException('Verse ID is required.');
        }

        $sql = 'SELECT t.*, r.startVerse, r.endVerse, r.rank, b.id AS bookId, b.name AS bookName, b.testament AS bookTestament '
             . 'FROM relations r '
             . sprintf('INNER JOIN %s t ON r.startVerse = t.id ', $this->getTable())
             . 'INNER JOIN books b ON t.bookId = b.id '
             . 'WHERE r.verseId = :verseId '
             . 'ORDER BY r.rank ASC';

        $stmt = $this->_db->prepare($sql);
        $stmt->execute(array(
            'verseId' => $options['verseId'],
        ));

        return $stmt->fetchAll();
    }

    /**
     * @param $startVerse
     * @param $endVerse
     * @return array
     */
    protected function _fetchGroupedRelations($startVerse, $endVerse)
    {
        $sql = 'SELECT t.*, b.id AS bookId, b.name AS bookName, b.testament AS bookTestament '
             . sprintf('FROM %s t ', $this->getTable())
             . 'INNER JOIN books b ON t.bookId = b.id '
             . 'WHERE t.id BETWEEN :start AND :end '
             . 'ORDER BY t.id ASC';

        $stmt = $this->_db->prepare($sql);
        $stmt->execute(array(
            'start' => $startVerse,
            'end'   => $endVerse
        ));

        return $stmt->fetchAll();
    }

    /**
     * @param $row
     * @return \Domain\Entity\RelationEntity
     */
    protected function _populateEntityFromRow($row)
    {
        $bookEntity = new BookEntity();
        $bookEntity->setId($row->bookId);
        $bookEntity->setName($row->bookName);
        $bookEntity->setTestament($row->bookTestament);

        $textEntity = new RelationEntity();
        $textEntity->setBook($bookEntity);
        $textEntity->populate($row);

        return $textEntity;
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
