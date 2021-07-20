<?php
namespace MonthlyBasis\User\Model\Service\Register\Errors;

use DateInterval;
use DateTime;
use MonthlyBasis\User\Model\Table as UserTable;

class Birthday
{
    public function __construct(
        UserTable\RegisterNotOldEnoughLog $registerNotOldEnoughLogTable
    ) {
        $this->registerNotOldEnoughLogTable = $registerNotOldEnoughLogTable;
    }

    public function getBirthdayErrors(): array
    {
        $errors = [];

        if (empty($_POST['birthday-month'])
            || empty($_POST['birthday-day'])
            || empty($_POST['birthday-year'])) {
            $errors[] = 'Invalid birthday.';
            return $errors;
        }

        $dateTime = DateTime::createFromFormat(
            'Y-m-d H:i:s',
            "{$_POST['birthday-year']}-{$_POST['birthday-month']}-{$_POST['birthday-day']} 23:59:59"
        );
        if (!$dateTime) {
            $errors[] = 'Invalid birthday.';
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
            $errors[] = 'Must be at least 13 years old to sign up.';
        }
        return $errors;
    }
}
