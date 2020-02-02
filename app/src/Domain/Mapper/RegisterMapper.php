<?php
namespace Domain\Mapper;

use Core\Domain\Mapper\AbstractMongoMapper;
use Core\Http\HttpBadRequestException;
use DateTime;

/**
 * Class RegisterMapper
 *
 * @package Domain\Mapper
 */
class RegisterMapper extends AbstractMongoMapper
{
    /**
     * @param array $data
     * @return array
     * @throws \Core\Http\HttpBadRequestException
     */
    public function register($data = array())
    {
        $errors = array();

        if (!isset($data['email'])) {
            $errors['email'] = 'Email is required.';
        } else {
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Invalid email.';
            }
        }

        if (!isset($data['password'])) {
            $errors['password'] = 'Password is required.';
        }

        if (!isset($data['passwordConf'])) {
            $errors['passwordConf'] = 'Password confirmation is required.';
        }

        if (isset($data['password'], $data['passwordConf']) && $data['password'] != $data['passwordConf']) {
            $errors['password'] = 'Password does not match password confirmation.';
        }

        if (isset($data['password']) && strlen($data['password']) < 5) {
            $errors['password'] = 'Password must be greater then 5 characters.';
        }

        if (count($errors) > 0) {
            $exception = new HttpBadRequestException();
            $exception->setDescription('Invalid request');
            $exception->setErrors($errors);

            throw $exception;
        }

        $collection = $this->getCollection('users');

        $result = $collection->findOne(array(
            'email' => $data['email']
        ));

        if ($result !== null) {
            $exception = new HttpBadRequestException();
            $exception->setDescription('Invalid request');
            $exception->setErrors(array(
                'email' => 'Email already exists.'
            ));

            throw $exception;
        }

        $collection->insertOne(array(
            'email' => filter_var($data['email'], FILTER_SANITIZE_EMAIL),
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'dateRegistered' => new DateTime(),
        ));

        return $data;
    }
}
