<?php
namespace Core\Http;

use Core\Domain\Entity\AbstractEntity;
use Core\Domain\Entity\Collection;

/**
 * Response
 *
 * @package Core\Http
 * @copyright Rob Keplin 2019
 */
class Response
{
    /**
     * @var int
     */
    protected $_code;

    /**
     * @var string
     */
    protected $_label;

    /**
     * @var mixed
     */
    protected $_content;

    /**
     * Response constructor.
     *
     * @param $content
     * @param $code
     * @param $label
     */
    public function __construct($content, $code, $label)
    {
        $this->setContent($content)
            ->setCode($code)
            ->setLabel($label);
    }
    /**
     * Prints out a json response
     */
    public function send()
    {
        $content = $this->getContent();

        if ($content instanceof Collection) {
            $content = $content->getItems();
        }

        if ($content instanceof AbstractEntity) {
            $content = $content->toArray();
        }

        header(sprintf('HTTP/1.1 %s %s', $this->getCode(), $this->getLabel()));
        header('Content-Type: application/json');
        echo json_encode($content);
        exit;
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return (int) $this->_code;
    }

    /**
     * @param int $code
     * @return $this
     */
    public function setCode($code)
    {
        $this->_code = $code;

        return $this;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return (string) $this->_label;
    }

    /**
     * @param string $label
     * @return $this
     */
    public function setLabel($label)
    {
        $this->_label = $label;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->_content;
    }

    /**
     * @param mixed $content
     * @return $this
     */
    public function setContent($content)
    {
        $this->_content = $content;

        return $this;
    }
}
