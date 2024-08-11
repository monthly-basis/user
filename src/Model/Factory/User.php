<?php
namespace LeoGalleguillos\User\Model\Factory;

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
        $userArray = $this->userTable->selectWhereUserId(
            $userId
        );

        return $this->createFromUserArray($userArray);
    }

    public function buildFromUsername(string $username)
    {
        $userArray = $this->userTable->selectWhereUsername(
            $username
        );

        if (empty($userArray)) {
            return false;
        }

        return $this->createFromUserArray($userArray);
    }

    public function createFromUserArray(array $userArray)
    {
        $userEntity = new UserEntity();

        $userEntity->userId   = $userArray['user_id'];
        $userEntity->username = $userArray['username'];
        return $userEntity;
    }
}
