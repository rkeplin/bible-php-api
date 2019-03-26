<?php
namespace Domain\Entity;

use Core\Domain\Entity\AbstractEntity;

/**
 * Class TranslationEntity
 *
 * @package Domain\Entity
 */
class TranslationEntity extends AbstractEntity
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $table;

    /**
     * @var string
     */
    protected $language;

    /**
     * @var string
     */
    protected $abbreviation;

    /**
     * @var string
     */
    protected $version;

    /**
     * @var string
     */
    protected $infoUrl;

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
     * @return string
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * @param string $table
     * @return $this
     */
    public function setTable($table)
    {
        $this->table = $table;

        return $this;
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param string $language
     * @return $this
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * @return string
     */
    public function getAbbreviation()
    {
        return $this->abbreviation;
    }

    /**
     * @param string $abbreviation
     * @return $this
     */
    public function setAbbreviation($abbreviation)
    {
        $this->abbreviation = $abbreviation;

        return $this;
    }

    /**
     * @return string
     */
    public function getInfoUrl()
    {
        return $this->infoUrl;
    }

    /**
     * @param string $infoUrl
     * @return $this
     */
    public function setInfoUrl($infoUrl)
    {
        $this->infoUrl = $infoUrl;

        return $this;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param string $version
     * @return $this
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }
}
