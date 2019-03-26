<?php
namespace Core\Domain\Entity;

use Iterator;

/**
 * Collection
 *
 * @package Core\Domain\Entity
 * @copyright Rob Keplin 2018
 */
class Collection implements Iterator
{
    /**
     * @var int
     */
    private $_position;

    /**
     * @var array
     */
    protected $_items;

    /**
     * Sets the position to 0 when a collection
     * is first created
     */
    public function __construct()
    {
        $this->_items = array();
        $this->_position = 0;
    }

    /**
     * Returns the collection of items
     *
     * @return array
     */
    public function getItems()
    {
        return (array) $this->_items;
    }

    /**
     * Adds the array representation of
     * the entity to the collection
     *
     * @param AbstractEntity $entity
     */
    public function addItem(AbstractEntity $entity)
    {
        $this->_items[] = $entity->toArray();
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    public function current()
    {
        return $this->_items[$this->_position];
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next()
    {
        ++$this->_position;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     */
    public function key()
    {
        return $this->_position;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid()
    {
        return isset($this->_items[$this->_position]);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        $this->_position = 0;
    }
}
