<?php
namespace Domain\Mapper;

use Core\Domain\Entity\Collection;
use Core\Domain\Mapper\AbstractMapper;
use Domain\Entity\TranslationEntity;

/**
 * Class TranslationMapper
 *
 * @package Domain\Mapper
 */
class TranslationMapper extends AbstractMapper
{
    /**
     * Model table
     *
     * @var string
     */
    protected $_table = 'translations';

    /**
     * Finds a bible translation by ID
     *
     * @param int $id
     * @return \Domain\Entity\TranslationEntity
     */
    public function findOneById($id)
    {
        $row = parent::findOneById($id);

        $entity = new TranslationEntity();
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
        $sql = 'SELECT * FROM translations ORDER BY version ASC;';

        $stmt = $this->_db->prepare($sql);
        $stmt->execute();

        $rows = $stmt->fetchAll();

        $collection = new Collection();

        foreach($rows as $row) {
            /* This one doesn't have NT data */
            if ($row->table == 't_wbt') {
                continue;
            }

            $entity = new TranslationEntity();
            $entity->populate($row);

            $collection->addItem($entity);
        }

        return $collection;
    }
}
