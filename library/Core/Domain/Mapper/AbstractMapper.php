<?php
namespace Core\Domain\Mapper;

use PDO;
use Exception;

/**
 * Core MVC Framework.
 *
 * @copyright Copyright (c) 2012 Rob Keplin.
 * @license TBD
 **/
abstract class AbstractMapper
{
    /**
     * @var PDO
     **/
    protected static $_defaultDb;

    /**
     * @var PDO
     **/
    protected $_db;

    /**
     * Database table name
     *
     * @var string
     */
    protected $_table = null;

    /**
     * If no PDO instance is given in the constructor,
     * the default one is used.
     *
     * @param PDO $db
     * @throws Exception
     * @return void
     **/
    public function __construct(PDO $db = null)
    {
        if(null === $db) {
            $db = self::$_defaultDb;
        }

        if(null === $db) {
            throw new Exception('No DB adapter was defined!');
        }

        $this->_db = $db;
        $this->_db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Sets the default PDO instance.
     *
     * @param PDO $db
     * @return void
     **/
    public static function setDefaultDb(PDO $db)
    {
        self::$_defaultDb = $db;
    }

    /**
     * Gets the default PDO instance.
     *
     * @param void
     * @return PDO
     **/
    public function getDb()
    {
        return $this->_db;
    }

    /**
     * Finds a row in the table.
     *
     * @param int $id
     * @return \stdClass
     */
    public function findOneById($id)
    {
        $sql = sprintf("SELECT * FROM %s WHERE id=:id", $this->_table);

        $stmt = $this->_db->prepare($sql);
        $stmt->execute(array(
            'id' => $id
        ));
        $row = $stmt->fetch();

        return $row;
    }

    /**
     * Finds all rows in the table
     *
     * @param string $conditions
     * @return array
     */
     public function findAll($conditions = null)
     {
         $sql = sprintf("SELECT * FROM %s", $this->_table);

         if($conditions) {
             $sql .= " WHERE {$conditions} ";
         }

         $sql .= " ORDER BY id DESC";

         $stmt = $this->_db->prepare($sql);
         $stmt->execute();
         $rows = $stmt->fetchAll();

         $collection = array();

         foreach($rows as $row) {
             $collection[] = $row;
         }

         return $collection;
     }

    /**
     * @return string
     */
    public function getTable()
    {
        return $this->_table;
    }

    /**
     * @param string $table
     * @return $this
     */
    public function setTable($table)
    {
        $this->_table = $table;

        return $this;
    }
}
