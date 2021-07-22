<?php
namespace MonthlyBasis\User\Model\Service\Register\Errors;

use DateInterval;
use DateTime;
use MonthlyBasis\User\Model\Table as UserTable;

class Birthday
{
    public const ERROR_NOT_OLD_ENOUGH   = 'Registration is currently unavailable.';
    public const ERROR_INVALID_BIRTHDAY = 'Invalid birthday.';

    public function __construct(
        UserTable\RegisterNotOldEnoughLog $registerNotOldEnoughLogTable
    ) {
        $this->registerNotOldEnoughLogTable = $registerNotOldEnoughLogTable;
    }

    public function getBirthdayErrors(): array
    {
        $errors = [];

        if (isset($_COOKIE['register-not-old-enough'])) {
            $errors[] = self::ERROR_NOT_OLD_ENOUGH;
            return $errors;
        }

        $result = $this->registerNotOldEnoughLogTable->selectWhereIpAddressAndCreatedGreaterThan(
            $_SERVER['REMOTE_ADDR'],
            (new DateTime())->modify('-30 days')
        );
        if (!empty($result->current())) {
            $errors[] = self::ERROR_NOT_OLD_ENOUGH;
            return $errors;
        }

        if (empty($_POST['birthday-month'])
            || empty($_POST['birthday-day'])
            || empty($_POST['birthday-year'])) {
            $errors[] = self::ERROR_INVALID_BIRTHDAY;
            return $errors;
        }

        $dateTime = DateTime::createFromFormat(
            'Y-m-d H:i:s',
            "{$_POST['birthday-year']}-{$_POST['birthday-month']}-{$_POST['birthday-day']} 23:59:59"
        );
        if (!$dateTime) {
            $errors[] = self::ERROR_INVALID_BIRTHDAY;
            return $errors;
        }

        /*
         * To safely acccount for different times of birth (hour, minute, second)
         * and to safely account for different timezones (which may be 23 or
         * more hours different from UTC time), technically, the
         * birthday which is parsed must be at least 13 years AND 2 days old,
         * relative to the current date and time.
         */
        $dateTime13YearsAnd2DaysAgo = (new DateTime())
            ->sub(new DateInterval('P13Y2D'));
        if ($dateTime13YearsAnd2DaysAgo < $dateTime) {
            setcookie(
                'register-not-old-enough',
                true,
                [
                    'domain'   => $_SERVER['HTTP_HOST'],
                    'expires'  => time() + 30 * 24 * 60 * 60,
                    'path'     => '/',
                    'samesite' => 'None',
                    'secure'   => true,
                ]
            );
            $this->registerNotOldEnoughLogTable->insert(
                $_SERVER['REMOTE_ADDR']
            );
            $errors[] = self::ERROR_NOT_OLD_ENOUGH;
        }
        return $errors;
    }
}
