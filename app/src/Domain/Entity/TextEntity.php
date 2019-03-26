<?php
namespace Domain\Entity;

use Core\Domain\Entity\AbstractEntity;

/**
 * Class TextEntity
 *
 * @package Domain\Entity
 */
class TextEntity extends AbstractEntity
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var \Domain\Entity\BookEntity
     */
    protected $book;

    /**
     * @var int
     */
    protected $chapterId;

    /**
     * @var int
     */
    protected $verseId;

    /**
     * @var string
     */
    protected $verse;

    /**
     * @return int
     */
    public function getId()
    {
        return (int) $this->id;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return BookEntity
     */
    public function getBook()
    {
        return $this->book;
    }

    /**
     * @param BookEntity $book
     * @return $this
     */
    public function setBook(BookEntity $book)
    {
        $this->book = $book;

        return $this;
    }

    /**
     * @return int
     */
    public function getChapterId()
    {
        return (int) $this->chapterId;
    }

    /**
     * @param int $chapterId
     * @return $this
     */
    public function setChapterId($chapterId)
    {
        $this->chapterId = $chapterId;

        return $this;
    }

    /**
     * @return int
     */
    public function getVerseId()
    {
        return (int) $this->verseId;
    }

    /**
     * @param int $verseId
     * @return $this
     */
    public function setVerseId($verseId)
    {
        $this->verseId = $verseId;

        return $this;
    }

    /**
     * @return string
     */
    public function getVerse()
    {
        return $this->verse;
    }

    /**
     * @param string $verse
     * @return $this
     */
    public function setVerse($verse)
    {
        $this->verse = $verse;

        return $this;
    }
}
