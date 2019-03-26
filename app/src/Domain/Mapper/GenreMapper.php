<?php
namespace Domain\Mapper;

use Core\Domain\Entity\Collection;
use Core\Domain\Mapper\AbstractMapper;
use Domain\Entity\GenreEntity;

/**
 * Class GenreMapper
 *
 * @package Domain\Mapper
 */
class GenreMapper extends AbstractMapper
{
    /**
     * Model table
     *
     * @var string
     */
    protected $_table = 'genres';

    /**
     * Finds a bible translation by ID
     *
     * @param int $id
     * @return \Domain\Entity\GenreEntity
     */
    public function findOneById($id)
    {
        $row = parent::findOneById($id);

        $entity = new GenreEntity();
        $entity->populate($row);

        return $entity;
    }

    /**
     * Finds bible translations
     *
     * @param array $options
     * @return \Core\Domain\Entity\Collection
     **/
    public function findAll($options = array())
    {
        $sql = 'SELECT * FROM genres ORDER BY id ASC;';

        $stmt = $this->_db->prepare($sql);
        $stmt->execute();

        $rows = $stmt->fetchAll();

        $collection = new Collection();

        foreach($rows as $row) {
            $entity = new GenreEntity();
            $entity->populate($row);

            $collection->addItem($entity);
        }

        return $collection;
    }
}
