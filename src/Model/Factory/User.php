<?php
namespace LeoGalleguillos\User\Model\Factory;

use ArrayObject;
use DateTime;
use LeoGalleguillos\User\Model\Entity as UserEntity;
use LeoGalleguillos\User\Model\Table\User as UserTable;

class User
{
    public function __construct(UserTable $userTable)
    {
        $this->userTable = $userTable;
    }

    public function buildFromUserId(int $userId)
    {
        $array = $this->userTable->selectWhereUserId(
            $userId
        );

        return $this->buildFromArrayObject($array);
    }

    /**
     * Build from array.
     *
     * @param array $array
     * @return UserEntity\User
     */
    public function buildFromArray(array $array) : UserEntity\User
    {
        $userEntity = new UserEntity\User();

        $userEntity->setUserId($array['user_id']);

        if (isset($array['username'])) {
            $userEntity->setUsername($array['username']);
        }

        if (isset($array['created'])) {
            $userEntity->setCreated(
                new DateTime($array['created'])
            );
        }

        $userEntity->setViews(
            (int) ($array['views'] ?? 0)
        );
        $userEntity->setWelcomeMessage(
            (string) ($array['welcome_message'] ?? '')
        );

        return $userEntity;
    }

    /**
     * Build from array object.
     *
     * @param ArrayObject $arrayObject
     * @return UserEntity\User
     * @deprecated Start using $this->buildFromArray(...) method instead
     */
    public function buildFromArrayObject($arrayObject)
    {
        $userEntity = new UserEntity\User();

        $userEntity->setUserId($arrayObject['user_id'])
                   ->setUsername($arrayObject['username']);

        if (isset($arrayObject['created'])) {
            $userEntity->setCreated(
                new DateTime($arrayObject['created'])
            );
        }

        $userEntity->setViews(
            (int) ($arrayObject['views'] ?? 0)
        );
        $userEntity->setWelcomeMessage(
            (string) ($arrayObject['welcome_message'] ?? '')
        );

        return $userEntity;
    }

    /**
     * Build from username.
     *
     * @param string $username
     * @return UserEntity\User
     */
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
