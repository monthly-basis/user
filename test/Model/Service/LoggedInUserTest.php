<?php
namespace MonthlyBasis\UserTest\Model\Service;

use Laminas\Db\Adapter\Driver\Pdo\Result;
use MonthlyBasis\LaminasTest\Hydrator as LaminasTestHydrator;
use MonthlyBasis\User\Model\Entity as UserEntity;
use MonthlyBasis\User\Model\Exception as UserException;
use MonthlyBasis\User\Model\Factory as UserFactory;
use MonthlyBasis\User\Model\Service as UserService;
use MonthlyBasis\User\Model\Table as UserTable;
use PHPUnit\Framework\TestCase;

class LoggedInUserTest extends TestCase
{
    protected function setUp(): void
    {
        $this->userFactoryMock = $this->createMock(
            UserFactory\User::class
        );
        $this->userUserTokenTableMock = $this->createMock(
            UserTable\UserUserToken::class
        );
        $this->loggedInUserService = new UserService\LoggedInUser(
            $this->userFactoryMock,
            $this->userUserTokenTableMock,
        );
    }

    public function test_getLoggedInUser_missingCookies_throwUserException()
    {
        unset($_COOKIE);

        try {
            $this->loggedInUserService->getLoggedInUser();
            $this->assertFail();
        } catch (UserException $userException) {
            $this->assertSame(
                'User is not logged in (cookies are not set).',
                $userException->getMessage()
            );
        }
    }

    public function test_getLoggedInUser_emptyRow_throwUserException()
    {
        unset($_COOKIE);
        $_COOKIE['user-id']     = 123;
        $_COOKIE['login-token'] = 'the-login-token';

        $countableIteratorHydrator = new LaminasTestHydrator\CountableIterator();
        $resultMock = $this->createMock(
            Result::class
        );
        $countableIteratorHydrator->hydrate(
            $resultMock,
            [],
        );

        $this->userUserTokenTableMock
            ->expects($this->once())
            ->method('selectWhereUserIdLoginTokenExpiresDeleted')
            ->with(123, 'the-login-token')
            ->willReturn($resultMock)
            ;

        try {
            $this->loggedInUserService->getLoggedInUser();
            $this->assertFail();
        } catch (UserException $userException) {
            $this->assertSame(
                'User is not logged in (could not find row).',
                $userException->getMessage()
            );
        }
    }

    public function test_getLoggedInUser_validCookiesAndNonEmptyRow_userEntity()
    {
        $_COOKIE = [
            'user-id'     => 123,
            'login-token' => 'the-login-token'
        ];

        $countableIteratorHydrator = new LaminasTestHydrator\CountableIterator();
        $resultMock = $this->createMock(
            Result::class
        );
        $array = [
            'user_id'       => '1',
            'username'      => 'username',
            'password_hash' => 'the-password-hash',
            'login_token'   => 'the-login-token',
            'https_token'   => 'the-https-token',
        ];
        $countableIteratorHydrator->hydrate(
            $resultMock,
            [
                $array,
            ],
        );

        $this->userUserTokenTableMock
            ->expects($this->once())
            ->method('selectWhereUserIdLoginTokenExpiresDeleted')
            ->with(123, 'the-login-token')
            ->willReturn($resultMock)
            ;
        $userEntity = new UserEntity\User();
        $this->userFactoryMock
            ->expects($this->once())
            ->method('buildFromArray')
            ->with($array)
            ->willReturn($userEntity)
            ;

        $this->assertSame(
            $userEntity,
            $this->loggedInUserService->getLoggedInUser()
        );
    }
}
