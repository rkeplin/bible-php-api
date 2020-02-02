<?php
namespace Core\Domain\Mapper;

use MongoDB\Client;
use Exception;

/**
 * Core MVC Framework.
 *
 * @copyright Copyright (c) 2020 Rob Keplin.
 * @license TBD
 **/
abstract class AbstractMongoMapper
{
    /**
     * @var \MongoDB\Client
     **/
    protected static $_defaultClient;

    /**
     * @var \MongoDB\Client
     **/
    protected $_client;

    /**
     * CollectionName name
     *
     * @var string
     */
    protected $_collectionName = null;

    /**
     * If no \MongoDB\Client instance is given in the constructor,
     * the default one is used.
     *
     * @param \MongoDB\Client $client
     * @throws Exception
     * @return void
     **/
    public function __construct(Client $client = null)
    {
        if(null === $client) {
            $client = self::$_defaultClient;
        }

        if(null === $client) {
            throw new Exception('No MongoDB Client was defined!');
        }

        $this->_client = $client;
    }

    /**
     * Sets the default \MongoDB\Client instance.
     *
     * @param \MongoDB\Client $client
     * @return void
     **/
    public static function setDefaultClient(Client $client)
    {
        self::$_defaultClient = $client;
    }

    /**
     * Gets the default \MongoDB\Client instance.
     *
     * @param void
     * @return \MongoDB\Client
     **/
    public function getClient()
    {
        return $this->_client;
    }

    /**
     * @param string $collectionName
     * @param string $dbName
     * @return \MongoDB\Collection
     * @throws \Exception
     */
    public function getCollection($collectionName = null, $dbName = null)
    {
        if ($collectionName == null) {
            $collectionName = $this->_collectionName;
        }

        if ($collectionName == null) {
            throw new Exception('Collection name not set.');
        }

        if ($dbName == null) {
            $dbName = getenv('MONGO_DB');
        }

        if ($dbName == null) {
            throw new Exception('DB name not set.');
        }

        return $this->getClient()->selectCollection($dbName, $collectionName);
    }

    /**
     * @return string
     */
    public function getCollectionName()
    {
        return $this->_collectionName;
    }

    /**
     * @param string $collectionName
     * @return $this
     */
    public function setCollectionName($collectionName)
    {
        $this->_collectionName = $collectionName;

        return $this;
    }
}
