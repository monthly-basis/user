<?php
namespace MonthlyBasis\UserTest\Model\Service\Register\Errors;

use DateInterval;
use DateTime;
use Laminas\Db\Adapter\Driver\Pdo\Result;
use MonthlyBasis\LaminasTest\Hydrator as LaminasTestHydrator;
use MonthlyBasis\User\Model\Service as UserService;
use MonthlyBasis\User\Model\Table as UserTable;
use PHPUnit\Framework\TestCase;

class BirthdayTest extends TestCase
{
    protected function setUp(): void
    {
        $this->countableIteratorHydrator = new LaminasTestHydrator\CountableIterator();

        $this->registerNotOldEnoughLogTableMock = $this->createMock(
            UserTable\RegisterNotOldEnoughLog::class
        );

        $this->birthdayService = new UserService\Register\Errors\Birthday(
            $this->registerNotOldEnoughLogTableMock
        );
    }

    /**
     * @runInSeparateProcess
     */
    public function test_getErrors_ipAddressWasLogged_nonEmptyErrors()
    {
        unset($_POST);

        $_SERVER['REMOTE_ADDR'] = '255.255.255.255';

        $nonEmptyResultMock = $this->createMock(
            Result::class
        );
        $this->countableIteratorHydrator->hydrate(
            $nonEmptyResultMock,
            [
                [
                    'register_not_old_enough_log_id' => '12345',
                    'ip_address'                     => '255.255.255.255',
                ],
            ],
        );

        $this->registerNotOldEnoughLogTableMock
             ->expects($this->once())
             ->method('selectWhereIpAddressAndCreatedGreaterThan')
             ->willReturn($nonEmptyResultMock)
             ;

        $this->registerNotOldEnoughLogTableMock
             ->expects($this->exactly(0))
             ->method('insert')
             ;

        $this->assertSame(
            [UserService\Register\Errors\Birthday::ERROR_NOT_OLD_ENOUGH],
            $this->birthdayService->getBirthdayErrors()
        );
    }

    /**
     * @runInSeparateProcess
     */
    public function test_getErrors_emptyPost_nonEmptyErrors()
    {
        unset($_POST);

        $_SERVER['REMOTE_ADDR'] = '255.255.255.255';

        $emptyResultMock = $this->createMock(
            Result::class
        );
        $this->countableIteratorHydrator->hydrate(
            $emptyResultMock,
            [],
        );

        $this->registerNotOldEnoughLogTableMock
             ->expects($this->once())
             ->method('selectWhereIpAddressAndCreatedGreaterThan')
             ->willReturn($emptyResultMock)
             ;

        $this->registerNotOldEnoughLogTableMock
             ->expects($this->exactly(0))
             ->method('insert')
             ;

        $this->assertSame(
            [UserService\Register\Errors\Birthday::ERROR_INVALID_BIRTHDAY],
            $this->birthdayService->getBirthdayErrors()
        );
    }

    /**
     * @runInSeparateProcess
     */
    public function test_getErrors_invalidBirthday_nonEmptyErrors()
    {
        $_POST = [];
        $_POST['birthday-month'] = 'invalid value';
        $_POST['birthday-day']   = 'invalid value';
        $_POST['birthday-year']  = 'invalid value';

        $_SERVER['REMOTE_ADDR'] = '255.255.255.255';

        $emptyResultMock = $this->createMock(
            Result::class
        );
        $this->countableIteratorHydrator->hydrate(
            $emptyResultMock,
            [],
        );

        $this->registerNotOldEnoughLogTableMock
             ->expects($this->exactly(0))
             ->method('insert')
             ;

        $this->assertSame(
            [UserService\Register\Errors\Birthday::ERROR_INVALID_BIRTHDAY],
            $this->birthdayService->getBirthdayErrors()
        );
    }

    /**
     * @runInSeparateProcess
     */
    public function test_getErrors_birthdayNotOldEnough_nonEmptyErrors()
    {
        $_SERVER['REMOTE_ADDR'] = '255.255.255.255';

        $emptyResultMock = $this->createMock(
            Result::class
        );
        $this->countableIteratorHydrator->hydrate(
            $emptyResultMock,
            [],
        );

        $this->registerNotOldEnoughLogTableMock
             ->expects($this->exactly(3))
             ->method('insert')
             ->with('255.255.255.255')
             ;

        $dateTime7YearsAgo = (new DateTime())->sub(new DateInterval('P7Y'));
        $_POST = [];
        $_POST['birthday-month'] = $dateTime7YearsAgo->format('m');
        $_POST['birthday-day']   = $dateTime7YearsAgo->format('d');
        $_POST['birthday-year']  = $dateTime7YearsAgo->format('Y');

        $this->assertSame(
            [UserService\Register\Errors\Birthday::ERROR_NOT_OLD_ENOUGH],
            $this->birthdayService->getBirthdayErrors()
        );

        $dateTime12YearsAgo = (new DateTime())->sub(new DateInterval('P12Y'));
        $_POST = [];
        $_POST['birthday-month'] = $dateTime12YearsAgo->format('m');
        $_POST['birthday-day']   = $dateTime12YearsAgo->format('d');
        $_POST['birthday-year']  = $dateTime12YearsAgo->format('Y');

        $this->assertSame(
            [UserService\Register\Errors\Birthday::ERROR_NOT_OLD_ENOUGH],
            $this->birthdayService->getBirthdayErrors()
        );

        $dateTime13YearsAgo = (new DateTime())->sub(new DateInterval('P13Y'));
        $_POST = [];
        $_POST['birthday-month'] = $dateTime13YearsAgo->format('m');
        $_POST['birthday-day']   = $dateTime13YearsAgo->format('d');
        $_POST['birthday-year']  = $dateTime13YearsAgo->format('Y');

        $this->assertSame(
            [UserService\Register\Errors\Birthday::ERROR_NOT_OLD_ENOUGH],
            $this->birthdayService->getBirthdayErrors()
        );
    }

    /**
     * @runInSeparateProcess
     */
    public function test_getErrors_birthdayOldEnough_emptyErrors()
    {
        $dateTime13YearsAnd3DaysAgo = (new DateTime())->sub(new DateInterval('P13Y3D'));
        $_POST = [];
        $_POST['birthday-month'] = $dateTime13YearsAnd3DaysAgo->format('m');
        $_POST['birthday-day']   = $dateTime13YearsAnd3DaysAgo->format('d');
        $_POST['birthday-year']  = $dateTime13YearsAnd3DaysAgo->format('Y');

        $_SERVER['REMOTE_ADDR'] = '255.255.255.255';

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
