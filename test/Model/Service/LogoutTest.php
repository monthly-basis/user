<?php
namespace MonthlyBasis\UserTest\Model\Service;

use MonthlyBasis\User\Model\Exception as UserException;
use MonthlyBasis\User\Model\Entity as UserEntity;
use MonthlyBasis\User\Model\Service as UserService;
use MonthlyBasis\User\Model\Table as UserTable;
use PHPUnit\Framework\TestCase;

class LogoutTest extends TestCase
{
    protected function setUp(): void
    {
        $_SERVER = [
            'HTTP_HOST' => 'example.com',
        ];
        $this->loggedInUserServiceMock = $this->createMock(
            UserService\LoggedInUser::class
        );
        $this->userTokenTableMock = $this->createMock(
            UserTable\UserToken::class
        );

        $this->logoutService = new UserService\Logout(
            $this->loggedInUserServiceMock,
            $this->userTokenTableMock
        );
    }

    /**
      * @runInSeparateProcess
      */
    public function test_logout_userRetrieved_null()
    {
        $userEntity = (new UserEntity\User())
            ->setUserId(2718)
            ->setLoginToken('the-login-token')
            ;
        $this->loggedInUserServiceMock
            ->expects($this->once())
            ->method('getLoggedInUser')
            ->willReturn($userEntity)
            ;
        $this->userTokenTableMock
            ->expects($this->once())
            ->method('updateSetDeletedWhereUserIdLoginToken')
            ->with(2718, 'the-login-token')
            ;

        $this->logoutService->logout();
    }

    /**
      * @runInSeparateProcess
      */
    public function test_logout_userExceptionThrown_null()
    {
        $this->loggedInUserServiceMock
            ->expects($this->once())
            ->method('getLoggedInUser')
            ->willThrowException(new UserException())
            ;
        $this->userTokenTableMock
            ->expects($this->exactly(0))
            ->method('updateSetDeletedWhereUserIdLoginToken')
            ;

        $this->logoutService->logout();
    }

    /**
      * @runInSeparateProcess
      */
    public function test_logout_userWithNoProperties_null()
    {
        $userEntity = (new UserEntity\User())
            ;
        $this->loggedInUserServiceMock
            ->expects($this->once())
            ->method('getLoggedInUser')
            ->willReturn($userEntity)
            ;
        $this->userTokenTableMock
            ->expects($this->exactly(0))
            ->method('updateSetDeletedWhereUserIdLoginToken')
            ;

        $this->logoutService->logout();
    }

    /**
      * @runInSeparateProcess
      */
    public function test_logout_userWithNoLoginToken_null()
    {
        $userEntity = (new UserEntity\User())
            ->setUserId(2718)
            ;
        $this->loggedInUserServiceMock
            ->expects($this->once())
            ->method('getLoggedInUser')
            ->willReturn($userEntity)
            ;
        $this->userTokenTableMock
            ->expects($this->exactly(0))
            ->method('updateSetDeletedWhereUserIdLoginToken')
            ;

        $this->logoutService->logout();
    }

    /**
      * @runInSeparateProcess
      */
    public function test_logout_userWithNoUserId_null()
    {
        $userEntity = (new UserEntity\User())
            ->setLoginToken('the-login-token')
            ;
        $this->loggedInUserServiceMock
            ->expects($this->once())
            ->method('getLoggedInUser')
            ->willReturn($userEntity)
            ;
        $this->userTokenTableMock
            ->expects($this->exactly(0))
            ->method('updateSetDeletedWhereUserIdLoginToken')
            ;

        $this->logoutService->logout();
    }
}
