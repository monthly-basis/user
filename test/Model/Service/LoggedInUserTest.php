<?php
namespace LeoGalleguillos\UserTest\Model\Service;

use Exception;
use LeoGalleguillos\User\Model\Factory as UserFactory;
use LeoGalleguillos\User\Model\Service as UserService;
use PHPUnit\Framework\TestCase;

class LoggedInUserTest extends TestCase
{
    protected function setUp()
    {
        $_SESSION['username'] = 'username';
        $this->userFactoryMock = $this->createMock(
            UserFactory\User::class
        );
        $this->loggedInUserService = new UserService\LoggedInUser(
            $this->userFactoryMock
        );
    }

    public function testInitialize()
    {
        $this->assertInstanceOf(
            UserService\LoggedInUser::class,
            $this->loggedInUserService
        );
    }

    public function testGetLoggedInUser()
    {
        try {
            $this->loggedInUserService->getLoggedInUser();
            $this->assertFail();
        } catch (Exception $exception) {
            $this->assertSame(
                'User is not logged in.',
                $exception->getMessage()
            );
        }
    }
}
