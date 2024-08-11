<?php
namespace MonthlyBasis\UserTest\Model\Service;

use Laminas\Db\Adapter\Driver\Pdo\Result;
use MonthlyBasis\LaminasTest\Hydrator as LaminasTestHydrator;
use MonthlyBasis\User\Model\Service as UserService;
use MonthlyBasis\User\Model\Table as UserTable;
use PHPUnit\Framework\TestCase;

class LoggedInTest extends TestCase
{
    protected function setUp(): void
    {
        $this->userUserTokenTableMock = $this->createMock(
            UserTable\UserUserToken::class
        );
        $this->loggedInService = new UserService\LoggedIn(
            $this->userUserTokenTableMock
        );
    }

    public function test_isLoggedIn_missingCookies_false()
    {
        unset($_COOKIE['user-id']);
        unset($_COOKIE['login-token']);

        $this->assertFalse(
            $this->loggedInService->isLoggedIn()
        );
    }

    public function test_isLoggedIn_emptyResult_false()
    {
        $_COOKIE['user-id']     = '123';
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

        $this->assertFalse(
            $this->loggedInService->isLoggedIn()
        );
    }

    public function test_isLoggedIn_validCookiesNonEmptyResult_true()
    {
        $_COOKIE['user-id']     = '123';
        $_COOKIE['login-token'] = 'the-login-token';

        $countableIteratorHydrator = new LaminasTestHydrator\CountableIterator();
        $resultMock = $this->createMock(
            Result::class
        );
        $countableIteratorHydrator->hydrate(
            $resultMock,
            [
                [
                    'user_id'       => '1',
                    'username'      => 'username',
                    'password_hash' => 'the-password-hash',
                    'login_token'   => 'the-login-token',
                    'https_token'   => 'the-https-token',
                ],
            ],
        );

        $this->userUserTokenTableMock
            ->expects($this->once())
            ->method('selectWhereUserIdLoginTokenExpiresDeleted')
            ->with(123, 'the-login-token')
            ->willReturn($resultMock)
             ;

        $this->assertTrue(
            $this->loggedInService->isLoggedIn()
        );
    }
}
