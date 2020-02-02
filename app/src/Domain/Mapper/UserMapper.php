<?php
namespace Domain\Mapper;

use Core\Domain\Mapper\AbstractMongoMapper;

/**
 * Class UserMapper
 *
 * @package Domain\Mapper
 */
class UserMapper extends AbstractMongoMapper
{
    public function findOne($options = array())
    {
        $collection = $this->getCollection('users');

        $result = $collection->findOne($options);

        if (!$result) {
            return false;
        }

        return array(
            'id' => (string) $result['_id'],
            'email' => $result['email'],
            'password' => $result['password'],
            'dateRegistered' => date('Y-m-d H:i:s', strtotime($result['dateRegistered']->date))
        );
    }
}
