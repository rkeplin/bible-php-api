<?php
namespace Domain\Entity;

/**
 * Class RelationEntity
 *
 * @package Domain\Entity
 */
class RelationEntity extends TextEntity
{
    /**
     * @var int
     */
    protected $group;

    /**
     * @var int
     */
    protected $rank;

    /**
     * @return int
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param int $group
     * @return $this
     */
    public function setGroup($group)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * @return int
     */
    public function getRank()
    {
        return (int) $this->rank;
    }

    /**
     * @param int $rank
     * @return $this
     */
    public function setRank($rank)
    {
        $this->rank = $rank;

        return $this;
    }
}
