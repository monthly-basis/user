<?php
namespace LeoGalleguillos\User\Model\Factory;

use ArrayObject;
use LeoGalleguillos\User\Model\Entity\User as UserEntity;
use LeoGalleguillos\User\Model\Table\User as UserTable;

class User
{
    public function __construct(UserTable $userTable)
    {
        $this->userTable = $userTable;
    }

    public function createFromUserId($userId)
    {
        $arrayObject = $this->userTable->selectWhereUserId(
            $userId
        );

        return $this->buildFromArrayObject($arrayObject);
    }

    public function buildFromArrayObject(ArrayObject $arrayObject)
    {
        $userEntity = new UserEntity();

        $userEntity->userId   = $arrayObject['user_id'];
        $userEntity->username = $arrayObject['username'];
        return $userEntity;
    }

    public function buildFromUsername(string $username)
    {
        $arrayObject = $this->userTable->selectWhereUsername(
            $username
        );

        if (empty($arrayObject)) {
            return false;
        }

        return $this->buildFromArrayObject($arrayObject);
    }
}
