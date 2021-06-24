<?php
namespace MonthlyBasis\User\Model\Factory\Password\Reset;

use DateTime;
use Exception;
use MonthlyBasis\User\Model\Entity as UserEntity;
use MonthlyBasis\User\Model\Table as UserTable;

class FromArray
{
    public function buildFromArray(array $array): UserEntity\Password\Reset
    {
        $resetEntity = (new UserEntity\Password\Reset())
            ->setCode($array['code'])
            ->setCreated(new DateTime($array['created']))
            ->setResetId($array['reset_password_id'])
            ->setUserId($array['user_id'])
            ;

        if (isset($array['accessed'])) {
            $resetEntity->setAccessed(
                new DateTime($array['accessed'])
            );
        }
        if (isset($array['used'])) {
            $resetEntity->setUsed(
                new DateTime($array['used'])
            );
        }

        return $resetEntity;
    }
}
