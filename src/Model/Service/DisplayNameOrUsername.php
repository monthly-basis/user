<?php
namespace LeoGalleguillos\User\Model\Service;

use LeoGalleguillos\User\Model\Entity as UserEntity;

class DisplayNameOrUsername
{
    public function getDisplayNameOrUsername(UserEntity\User $userEntity)
    {
        try {
            return $userEntity->getDisplayName();
        } catch (TypeError $typeError) {
            return $userEntity->getUsername();
        }
    }
}
