<?php
namespace LeoGalleguillos\User\Model\Service;

use LeoGalleguillos\User\Model\Entity\User as UserEntity;

class User
{
    /**
     * Get full name.
     *
     * @return string
     */
    public function getFullName(UserEntity $userEntity)
    {
        return $userEntity->firstName . ' ' . $userEntity->lastName;
    }
}
