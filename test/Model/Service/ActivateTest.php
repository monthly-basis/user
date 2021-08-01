<?php
namespace MonthlyBasis\UserTest\Model\Service;

use Laminas\Db\Adapter\Driver\Pdo\Connection;
use Laminas\Db\Adapter\Driver\Pdo\Result;
use Laminas\Db\Adapter\Exception\InvalidQueryException;
use MonthlyBasis\LaminasTest\Hydrator as LaminasTestHydrator;
use MonthlyBasis\User\Model\Service as UserService;
use MonthlyBasis\User\Model\Table as UserTable;
use PHPUnit\Framework\TestCase;

class ActivateTest extends TestCase
{
    protected function setUp(): void
    {
        $this->connectionMock = $this->createMock(
            Connection::class
        );
        $this->activateLogTableMock = $this->createMock(
            UserTable\ActivateLog::class
        );
        $this->registerTableMock = $this->createMock(
            UserTable\Register::class
        );
        $this->userTableMock = $this->createMock(
            UserTable\User::class
        );
        $this->userEmailTableMock = $this->createMock(
            UserTable\UserEmail::class
        );
        $this->activateService = new UserService\Activate(
            $this->connectionMock,
            $this->activateLogTableMock,
            $this->registerTableMock,
            $this->userTableMock,
            $this->userEmailTableMock,
        );

        $this->countableIteratorHydrator = new LaminasTestHydrator\CountableIterator();
    }

    public function test_activate_tooManyFailedAttempts_false()
    {
        $_SERVER['REMOTE_ADDR'] = '1.2.4.8';

        $activateLogResultMock = $this->createMock(
            Result::class
        );
        $this->countableIteratorHydrator->hydrate(
            $activateLogResultMock,
            [
                [
                    'COUNT(*)' => 3,
                ],
            ],
        );
        $this->activateLogTableMock
             ->expects($this->exactly(1))
             ->method('selectCountWhereIpAddressAndSuccess')
             ->with(
                '1.2.4.8',
                false,
             )
             ->willReturn(
                $activateLogResultMock
             )
             ;
        $this->registerTableMock
             ->expects($this->exactly(0))
             ->method('selectWhereRegisterIdAndActivationCode')
             ;
        $this->activateLogTableMock
             ->expects($this->exactly(0))
             ->method('insert')
             ;
        $this->connectionMock
             ->expects($this->exactly(0))
             ->method('beginTransaction')
             ;
        $this->userTableMock
             ->expects($this->exactly(0))
             ->method('insert')
             ;
        $this->userEmailTableMock
             ->expects($this->exactly(0))
             ->method('insert')
             ;
        $this->registerTableMock
             ->expects($this->exactly(0))
             ->method('updateSetActivatedWhereRegisterId')
             ;
        $this->connectionMock
             ->expects($this->exactly(0))
             ->method('rollback')
             ;
        $this->connectionMock
             ->expects($this->exactly(0))
             ->method('commit')
             ;

        $this->assertFalse(
            $this->activateService->activate(12345, 'invalid-activation-code')
        );
    }

    public function test_activate_invalidActivationCode_false()
    {
        $_SERVER['REMOTE_ADDR'] = '1.2.4.8';

        $activateLogResultMock = $this->createMock(
            Result::class
        );
        $this->countableIteratorHydrator->hydrate(
            $activateLogResultMock,
            [
                [
                    'COUNT(*)' => 2,
                ],
            ],
        );
        $this->activateLogTableMock
             ->expects($this->exactly(1))
             ->method('selectCountWhereIpAddressAndSuccess')
             ->with(
                '1.2.4.8',
                false,
             )
             ->willReturn(
                $activateLogResultMock
             )
             ;
        $registerResultMock = $this->createMock(
            Result::class
        );
        $this->countableIteratorHydrator->hydrate(
            $registerResultMock,
            [],
        );
        $this->registerTableMock
             ->expects($this->once())
             ->method('selectWhereRegisterIdAndActivationCode')
             ->with(12345, 'invalid-activation-code')
             ->willReturn($registerResultMock)
             ;
        $this->activateLogTableMock
             ->expects($this->exactly(1))
             ->method('insert')
             ->with(
                '1.2.4.8',
                false,
             )
             ;
        $this->connectionMock
             ->expects($this->exactly(0))
             ->method('beginTransaction')
             ;
        $this->userTableMock
             ->expects($this->exactly(0))
             ->method('insert')
             ;
        $this->userEmailTableMock
             ->expects($this->exactly(0))
             ->method('insert')
             ;
        $this->registerTableMock
             ->expects($this->exactly(0))
             ->method('updateSetActivatedWhereRegisterId')
             ;
        $this->connectionMock
             ->expects($this->exactly(0))
             ->method('rollback')
             ;
        $this->connectionMock
             ->expects($this->exactly(0))
             ->method('commit')
             ;

        $this->assertFalse(
            $this->activateService->activate(12345, 'invalid-activation-code')
        );
    }

    public function test_activate_invalidQueryExceptionIsThrown_rollbackAndReturnFalse()
    {
        $activateLogResultMock = $this->createMock(
            Result::class
        );
        $this->countableIteratorHydrator->hydrate(
            $activateLogResultMock,
            [
                [
                    'COUNT(*)' => 0,
                ],
            ],
        );
        $this->activateLogTableMock
             ->expects($this->exactly(1))
             ->method('selectCountWhereIpAddressAndSuccess')
             ->with(
                '1.2.4.8',
                false,
             )
             ->willReturn(
                $activateLogResultMock
             )
             ;
        $registerResultMock = $this->createMock(
            Result::class
        );
        $this->countableIteratorHydrator->hydrate(
            $registerResultMock,
            [
                [
                    'username'      => 'username',
                    'email'         => 'email@example.com',
                    'password_hash' => 'password-hash',
                    'birthday'      => '2000-07-30 12:34:56',
                    'gender'        => null,
                ],
            ],
        );
        $this->registerTableMock
             ->expects($this->once())
             ->method('selectWhereRegisterIdAndActivationCode')
             ->with(54321, 'valid-code')
             ->willReturn($registerResultMock)
             ;
        $this->connectionMock
             ->expects($this->exactly(1))
             ->method('beginTransaction')
             ;
        $this->userTableMock
             ->expects($this->exactly(1))
             ->method('insert')
             ->with(
                'username',
                'password-hash',
                '2000-07-30 12:34:56',
                null,
             )
             ->willReturn(12345);
             ;
        $this->userEmailTableMock
             ->expects($this->exactly(1))
             ->method('insert')
             ->with(
                12345,
                'email@example.com',
             )
             ->will(
                $this->throwException(new InvalidQueryException())
             )
             ;
        $this->registerTableMock
             ->expects($this->exactly(0))
             ->method('updateSetActivatedWhereRegisterId')
             ;
        $this->connectionMock
             ->expects($this->exactly(1))
             ->method('rollback')
             ;
        $this->connectionMock
             ->expects($this->exactly(0))
             ->method('commit')
             ;
        $this->activateLogTableMock
             ->expects($this->exactly(0))
             ->method('insert')
             ;

        $this->assertFalse(
            $this->activateService->activate(54321, 'valid-code')
        );
    }

    public function test_activate_validRegisterIdAndActivationCode_true()
    {
        $activateLogResultMock = $this->createMock(
            Result::class
        );
        $this->countableIteratorHydrator->hydrate(
            $activateLogResultMock,
            [
                [
                    'COUNT(*)' => 1,
                ],
            ],
        );
        $this->activateLogTableMock
             ->expects($this->exactly(1))
             ->method('selectCountWhereIpAddressAndSuccess')
             ->with(
                '1.2.4.8',
                false,
             )
             ->willReturn(
                $activateLogResultMock
             )
             ;
        $registerResultMock = $this->createMock(
            Result::class
        );
        $this->countableIteratorHydrator->hydrate(
            $registerResultMock,
            [
                [
                    'username'      => 'username',
                    'email'         => 'email@example.com',
                    'password_hash' => 'password-hash',
                    'birthday'      => '2000-07-30 12:34:56',
                    'gender'        => null,
                ],
            ],
        );

        $this->registerTableMock
             ->expects($this->once())
             ->method('selectWhereRegisterIdAndActivationCode')
             ->with(54321, 'valid-code')
             ->willReturn($registerResultMock)
             ;
        $this->connectionMock
             ->expects($this->exactly(1))
             ->method('beginTransaction')
             ;
        $this->userTableMock
             ->expects($this->exactly(1))
             ->method('insert')
             ->with(
                'username',
                'password-hash',
                '2000-07-30 12:34:56',
                null,
             )
             ->willReturn(12345);
             ;
        $this->userEmailTableMock
             ->expects($this->exactly(1))
             ->method('insert')
             ->with(
                12345,
                'email@example.com',
             )
             ;
        $this->registerTableMock
             ->expects($this->exactly(1))
             ->method('updateSetActivatedWhereRegisterId')
             ->with(
                true,
                54321,
             )
             ;
        $this->connectionMock
             ->expects($this->exactly(0))
             ->method('rollback')
             ;
        $this->connectionMock
             ->expects($this->exactly(1))
             ->method('commit')
             ;
        $this->activateLogTableMock
             ->expects($this->exactly(1))
             ->method('insert')
             ->with(
                '1.2.4.8',
                true,
             )
             ;

        $this->assertTrue(
            $this->activateService->activate(54321, 'valid-code')
        );
    }
}
