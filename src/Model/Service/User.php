<?php
namespace LeoGalleguillos\User\Model\Service;

use LeoGalleguillos\User\Model\Entity as UserEntity;
use LeoGalleguillos\User\Model\Table as UserTable;

class User
{
    public function __construct(
        UserTable\User $userTable
    ) {
        $this->userTable = $userTable;
    }
    public function incrementViews(UserEntity\User $userEntity)
    {
        return $userEntity->firstName . ' ' . $userEntity->lastName;
    }
}
