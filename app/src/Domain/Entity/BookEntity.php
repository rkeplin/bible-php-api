<?php
namespace Domain\Entity;

use Core\Domain\Entity\AbstractEntity;

/**
 * Class BookEntity
 *
 * @package Domain\Entity
 */
class BookEntity extends AbstractEntity
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $testament;

    /**
     * @var \Domain\Entity\GenreEntity
     */
    protected $genre;

    /**
     * @return mixed
     */
    public function getId()
    {
        return (int) $this->id;
    }

    /**
     * @param mixed $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getTestament()
    {
        if ($this->testament == 'OT') {
            return 'Old Testament';
        }

        if ($this->testament == 'NT') {
            return 'New Testament';
        }

        return $this->testament;
    }

    /**
     * @param string $testament
     * @return $this
     */
    public function setTestament($testament)
    {
        $this->testament = $testament;

        return $this;
    }

    /**
     * @return GenreEntity
     */
    public function getGenre()
    {
        return $this->genre;
    }

    /**
     * @param GenreEntity $genre
     * @return $this
     */
    public function setGenre(GenreEntity $genre)
    {
        $this->genre = $genre;

        return $this;
    }
}
