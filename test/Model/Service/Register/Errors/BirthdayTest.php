<?php
namespace MonthlyBasis\UserTest\Model\Service\Register\Errors;

use DateInterval;
use DateTime;
use MonthlyBasis\User\Model\Service as UserService;
use PHPUnit\Framework\TestCase;

class BirthdayTest extends TestCase
{
    protected function setUp(): void
    {
        $this->birthdayService = new UserService\Register\Errors\Birthday();
    }

    public function test_getErrors_emptyPost_nonEmptyErrors()
    {
        unset($_POST);

        $this->assertSame(
            ['Invalid birthday.'],
            $this->birthdayService->getBirthdayErrors()
        );
    }

    public function test_getErrors_invalidBirthday_nonEmptyErrors()
    {
        $_POST = [];
        $_POST['birthday-month'] = 'invalid value';
        $_POST['birthday-day']   = 'invalid value';
        $_POST['birthday-year']  = 'invalid value';

        $this->assertSame(
            ['Invalid birthday.'],
            $this->birthdayService->getBirthdayErrors()
        );
    }

    public function test_getErrors_birthdayNotOldEnough_nonEmptyErrors()
    {
        $dateTime7YearsAgo = (new DateTime())->sub(new DateInterval('P7Y'));
        $_POST = [];
        $_POST['birthday-month'] = $dateTime7YearsAgo->format('m');
        $_POST['birthday-day']   = $dateTime7YearsAgo->format('d');
        $_POST['birthday-year']  = $dateTime7YearsAgo->format('Y');

        $this->assertSame(
            ['Must be at least 13 years old to sign up.'],
            $this->birthdayService->getBirthdayErrors()
        );

        $dateTime12YearsAgo = (new DateTime())->sub(new DateInterval('P12Y'));
        $_POST = [];
        $_POST['birthday-month'] = $dateTime12YearsAgo->format('m');
        $_POST['birthday-day']   = $dateTime12YearsAgo->format('d');
        $_POST['birthday-year']  = $dateTime12YearsAgo->format('Y');

        $this->assertSame(
            ['Must be at least 13 years old to sign up.'],
            $this->birthdayService->getBirthdayErrors()
        );

        $dateTime13YearsAgo = (new DateTime())->sub(new DateInterval('P13Y'));
        $_POST = [];
        $_POST['birthday-month'] = $dateTime13YearsAgo->format('m');
        $_POST['birthday-day']   = $dateTime13YearsAgo->format('d');
        $_POST['birthday-year']  = $dateTime13YearsAgo->format('Y');

        $this->assertSame(
            ['Must be at least 13 years old to sign up.'],
            $this->birthdayService->getBirthdayErrors()
        );
    }

    public function test_getErrors_birthdayOldEnough_emptyErrors()
    {
        $dateTime13YearsAnd3DaysAgo = (new DateTime())->sub(new DateInterval('P13Y3D'));
        $_POST = [];
        $_POST['birthday-month'] = $dateTime13YearsAnd3DaysAgo->format('m');
        $_POST['birthday-day']   = $dateTime13YearsAnd3DaysAgo->format('d');
        $_POST['birthday-year']  = $dateTime13YearsAnd3DaysAgo->format('Y');

        $this->assertSame(
            [],
            $this->birthdayService->getBirthdayErrors()
        );

        $dateTime14YearsAgo = (new DateTime())->sub(new DateInterval('P14Y'));
        $_POST = [];
        $_POST['birthday-month'] = $dateTime14YearsAgo->format('m');
        $_POST['birthday-day']   = $dateTime14YearsAgo->format('d');
        $_POST['birthday-year']  = $dateTime14YearsAgo->format('Y');

        $this->assertSame(
            [],
            $this->birthdayService->getBirthdayErrors()
        );

        $dateTime37YearsAgo = (new DateTime())->sub(new DateInterval('P37Y'));
        $_POST = [];
        $_POST['birthday-month'] = $dateTime37YearsAgo->format('m');
        $_POST['birthday-day']   = $dateTime37YearsAgo->format('d');
        $_POST['birthday-year']  = $dateTime37YearsAgo->format('Y');

        $this->assertSame(
            [],
            $this->birthdayService->getBirthdayErrors()
        );

        $dateTime100YearsAgo = (new DateTime())->sub(new DateInterval('P1000Y'));
        $_POST = [];
        $_POST['birthday-month'] = $dateTime100YearsAgo->format('m');
        $_POST['birthday-day']   = $dateTime100YearsAgo->format('d');
        $_POST['birthday-year']  = $dateTime100YearsAgo->format('Y');

        $this->assertSame(
            [],
            $this->birthdayService->getBirthdayErrors()
        );
    }
}
