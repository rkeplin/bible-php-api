<?php
namespace Domain\Mapper;

use Core\Domain\Mapper\AbstractMongoMapper;
use Core\Http\HttpBadRequestException;
use Core\Http\HttpNotFoundException;
use Datetime;
use Domain\Service\TranslationFactory;
use MongoDB\BSON\ObjectId;

/**
 * Class ListMapper
 *
 * @package Domain\Mapper
 */
class ListMapper extends AbstractMongoMapper
{
    /**
     * @const string
     */
    const LIST_NAME_REGEX = '/^[a-zA-Z0-9\s]{1,50}$/';

    /**
     * @const int
     */
    const MAX_LISTS_PER_USER = 200;

    /**
     * @const int
     */
    const MAX_VERSES_PER_LIST = 500;

    /**
     * @var \Domain\Mapper\UserMapper
     */
    protected $_userMapper;

    /**
     * @var \Domain\Mapper\TextMapper
     */
    protected $_textMapper;

    /**
     * Creates list for specified user
     *
     * @param $userId
     * @param $data
     * @throws HttpBadRequestException
     * @throws \Exception
     */
    public function create($userId, $data)
    {
        $errors = array();

        if (!isset($data['name'])) {
            $errors['name'] = 'List name is required.';
        }

        if (!preg_match(self::LIST_NAME_REGEX, $data['name'])) {
            $errors['name'] = 'Invalid list name.';
        }

        $user = $this->getUserMapper()->findOne(array(
            '_id' => new ObjectId($userId)
        ));

        if (!$user) {
            $errors['user'] = 'User not found.';
        }

        $collection = $this->getCollection('lists');

        $count = $collection->countDocuments(array(
            'user._id' => new ObjectId($userId)
        ));

        if ($count >= self::MAX_LISTS_PER_USER) {
            $errors['user'] = 'Maximum number of lists for account has been reached.';
        }

        if (count($errors) > 0) {
            $exception = new HttpBadRequestException();
            $exception->setDescription('Invalid request');
            $exception->setErrors($errors);

            throw $exception;
        }

        $result = $collection->insertOne(array(
            'user' => array(
                '_id' => new ObjectId($user['id']),
                'email' => $user['email']
            ),
            'name' => filter_var($data['name'], FILTER_SANITIZE_STRING),
            'dateAdded' => new DateTime(),
            'dateUpdated' => new DateTime()
        ));

        if (!$result->getInsertedCount()) {
            return false;
        }

        return $this->findOne(array(
            '_id' => $result->getInsertedId()
        ));
    }

    /**
     * Updates a list
     *
     * @param $userId
     * @param $id
     * @param $data
     * @return array|bool
     * @throws \Core\Http\HttpBadRequestException
     * @throws \Core\Http\HttpNotFoundException
     * @throws \Exception
     */
    public function update($userId, $id, $data)
    {
        $errors = array();

        if (!isset($data['name'])) {
            $errors['name'] = 'List name is required.';
        }

        if (!preg_match(self::LIST_NAME_REGEX, $data['name'])) {
            $errors['name'] = 'Invalid list name.';
        }

        $user = $this->getUserMapper()->findOne(array(
            '_id' => new ObjectId($userId)
        ));

        if (!$user) {
            $exception = new HttpNotFoundException();
            $exception->setDescription('User not found.');

            throw $exception;
        }

        $list = $this->findOne(array(
            'user._id' => new ObjectId($userId),
            '_id' => new ObjectId($id)
        ));

        if (!$list) {
            $exception = new HttpNotFoundException();
            $exception->setDescription('List not found.');

            throw $exception;
        }

        if (count($errors) > 0) {
            $exception = new HttpBadRequestException();
            $exception->setDescription('Invalid request');
            $exception->setErrors($errors);

            throw $exception;
        }

        $collection = $this->getCollection('lists');

        $collection->updateOne(array(
            'user._id' => new ObjectId($userId),
            '_id' => new ObjectId($id)
        ), array(
            '$set' => array(
                'name' => filter_var($data['name'], FILTER_SANITIZE_STRING),
                'dateUpdated' => new DateTime()
            )
        ));

        return $this->findOne(array(
            '_id' => new ObjectId($id)
        ));
    }

    /**
     * Deletes a list
     *
     * @param $userId
     * @param $id
     * @throws \Exception
     */
    public function delete($userId, $id)
    {
        /* Remove the verses on the list */
        $collection = $this->getCollection('verses');

        $collection->deleteMany(array(
            'user._id' => new ObjectId($userId),
            'list._id' => new ObjectId($id)
        ));

        /* Remove the list */
        $collection = $this->getCollection('lists');

        $collection->deleteOne(array(
            'user._id' => new ObjectId($userId),
            '_id' => new ObjectId($id)
        ));
    }

    /**
     * Finds a list
     *
     * @param $options
     * @return array|bool
     * @throws \Exception
     */
    public function findOne($options)
    {
        $collection = $this->getCollection('lists');

        $item = $collection->findOne($options);

        if (!$item) {
            return false;
        }

        return $this->_format($item);
    }

    /**
     * Finds many lists
     *
     * @param array $options
     * @return array
     * @throws \Exception
     */
    public function findAll($options = array())
    {
        $collection = $this->getCollection('lists');

        $items = $collection->find($options);

        if (!$items) {
            return array();
        }

        $data = array();

        foreach ($items as $item) {
            $data[] = $this->_format($item);
        }

        return $data;
    }

    /**
     * Puts a verse on a list
     *
     * @param int $userId
     * @param int $listId
     * @param int $verseId
     * @param string|null $translation
     * @return bool
     * @throws HttpBadRequestException
     * @throws HttpNotFoundException
     * @throws \Exception
     */
    public function putVerse($userId, $listId, $verseId, $translation = null)
    {
        $user = $this->getUserMapper()->findOne(array(
            '_id' => new ObjectId($userId)
        ));

        if (!$user) {
            $exception = new HttpNotFoundException();
            $exception->setDescription('User not found.');

            throw $exception;
        }

        $this->getTextMapper()->setTable(
            TranslationFactory::getTranslationTable($translation)
        );

        $textEntity = $this->getTextMapper()->findOneById($verseId);

        if (!$textEntity) {
            $exception = new HttpNotFoundException();
            $exception->setDescription('Verse not found.');

            throw $exception;
        }

        $list = $this->findOne(array(
            'user._id' => new ObjectId($userId),
            '_id' => new ObjectId($listId)
        ));

        if (!$list) {
            $exception = new HttpNotFoundException();
            $exception->setDescription('List not found.');

            throw $exception;
        }

        $collection = $this->getCollection('verses');

        $count = $collection->countDocuments(array(
            'user._id' => new ObjectId($userId),
            'list._id' => new ObjectId($listId)
        ));

        if ($count >= self::MAX_VERSES_PER_LIST) {
            $exception = new HttpBadRequestException();
            $exception->setDescription('Maximum number of verses have been added to this list.');

            throw $exception;
        }

        $count = $collection->countDocuments(array(
            'user._id' => new ObjectId($userId),
            'list._id' => new ObjectId($listId),
            'text.id' => (int) $verseId,
            'translation' => strtoupper($translation)
        ));

        if ($count > 0) {
            $exception = new HttpBadRequestException();
            $exception->setDescription('This verse is already in the list.');

            throw $exception;
        }

        $result = $collection->insertOne(array(
            'user' => array(
                '_id' => new ObjectId($userId),
                'email' => $user['email']
            ),
            'list' => array(
                '_id' => new ObjectId($listId)
            ),
            'text' => $textEntity->toArray(),
            'translation' => strtoupper($translation),
            'dateAdded' => new DateTime()
        ));

        if (!$result->getInsertedCount()) {
            return false;
        }

        if ($result->getInsertedCount() != 1) {
            return false;
        }

        return true;
    }

    /**
     * Finds all verses from a list
     *
     * @param $userId
     * @param $listId
     * @return array
     * @throws \Core\Http\HttpNotFoundException
     * @throws \Exception
     */
    public function findAllVerses($userId, $listId)
    {
        $user = $this->getUserMapper()->findOne(array(
            '_id' => new ObjectId($userId)
        ));

        if (!$user) {
            $exception = new HttpNotFoundException();
            $exception->setDescription('User not found.');

            throw $exception;
        }

        $list = $this->findOne(array(
            'user._id' => new ObjectId($userId),
            '_id' => new ObjectId($listId)
        ));

        if (!$list) {
            $exception = new HttpNotFoundException();
            $exception->setDescription('List not found.');

            throw $exception;
        }

        $collection = $this->getCollection('verses');

        $items = $collection->find(array(
            'user._id' => new ObjectId($userId),
            'list._id' => new ObjectId($listId)
        ));

        if (!$items) {
            return array();
        }

        $data = array();

        foreach ($items as $item) {
            $data[] = $this->_formatVerse($item);
        }

        return $data;
    }

    /**
     * Removes a verse from a list
     *
     * @param $userId
     * @param $listId
     * @param $verseId
     * @param $translation
     * @throws \Exception
     */
    public function deleteVerse($userId, $listId, $verseId, $translation = null)
    {
        $collection = $this->getCollection('verses');

        $collection->deleteOne(array(
            'user._id' => new ObjectId($userId),
            'list._id' => new ObjectId($listId),
            'text.id' => (int) $verseId,
            'translation' => strtoupper($translation)
        ));
    }

    /**
     * Formats a list item
     *
     * @param $item
     * @return array
     */
    protected function _format($item)
    {
        $formatted = array(
            'id' => (string) $item['_id'],
            'user' => array(
                'id' => (string) $item['user']['_id'],
                'email' => $item['user']['email']
            ),
            'name' => $item['name'],
            'dateAdded' => date('Y-m-d H:i:s', strtotime($item['dateAdded']->date))
        );

        if (isset($item['dateUpdated'])) {
            $formatted['dateUpdated'] = date('Y-m-d H:i:s', strtotime($item['dateUpdated']->date));
        }

        return $formatted;
    }

    /**
     * Formats a verse item
     *
     * @param $item
     * @return array
     */
    protected function _formatVerse($item)
    {
        return array(
            'id' => (string) $item['_id'],
            'user' => array(
                'id' => (string) $item['user']['_id'],
                'email' => $item['user']['email']
            ),
            'list' => array(
                'id' => (string) $item['list']['_id']
            ),
            'text' => $item['text'],
            'translation' => strtoupper($item['translation']),
            'dateAdded' => date('Y-m-d H:i:s', strtotime($item['dateAdded']->date))
        );
    }

    /**
     * @return \Domain\Mapper\UserMapper
     * @throws \Exception
     */
    public function getUserMapper()
    {
        if ($this->_userMapper == null) {
            $this->_userMapper = new UserMapper();
        }

        return $this->_userMapper;
    }

    /**
     * @param \Domain\Mapper\UserMapper $userMapper
     */
    public function setUserMapper(UserMapper $userMapper)
    {
        $this->_userMapper = $userMapper;

        return $this;
    }

    /**
     * @return \Domain\Mapper\TextMapper
     * @throws \Exception
     */
    public function getTextMapper()
    {
        if ($this->_textMapper == null) {
            $this->_textMapper = new TextMapper();
        }
        
        return $this->_textMapper;
    }

    /**
     * @param \Domain\Mapper\TextMapper $textMapper
     * @return $this
     */
    public function setTextMapper(TextMapper $textMapper)
    {
        $this->_textMapper = $textMapper;
        
        return $this;
    }
}
