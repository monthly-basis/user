<?php
namespace LeoGalleguillos\User\Model\Service;

use LeoGalleguillos\User\Model\Entity as UserEntity;
use TypeError;

class DisplayNameOrUsername
{
    public function getDisplayNameOrUsername(UserEntity\User $userEntity): string
    {
        try {
            return $userEntity->getDisplayName();
        } catch (TypeError $typeError) {
            return $userEntity->getUsername();
        }
    }
}
