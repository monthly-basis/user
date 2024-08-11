<?php
namespace MonthlyBasis\User\Model\Service\Password\Reset;

use Error;
use DateTime;
use MonthlyBasis\User\Model\Entity as UserEntity;

class Expired
{
    public function isExpired(UserEntity\Password\Reset $resetEntity): bool
    {
        $dateTimeOneDayAgo = (new DateTime())
            ->modify('-1 day');

        if ($resetEntity->getCreated() < $dateTimeOneDayAgo) {
            return true;
        }

        try {
            $accessed = $resetEntity->getAccessed();

            $dateTimeFiveMinutesAgo = (new DateTime())
                ->modify('-5 minutes');

            if ($accessed < $dateTimeFiveMinutesAgo) {
                return true;
            }
        } catch (Error $error) {
            // Do nothing.
        }

        try {
            $used = $resetEntity->getUsed();
            return true;
        } catch (Error $error) {
            // Do nothing.
        }

        return false;
    }
}
