<?php
namespace LeoGalleguillos\User\Model\Service;

use LeoGalleguillos\User\Model\Entity as UserEntity;

class RootRelativeUrl
{
    /**
     * Get root-relative URL
     *
     * @param UserEntity\User $userEntity
     * @return string
     */
    public function getRootRelativeUrl(UserEntity\User $userEntity)
    {
        return '/users/'
             . $userEntity->getUserId()
             . '/'
             . $userEntity->getUsername();
    }
}