<?php
namespace LeoGalleguillos\User\Model\Service;

use LeoGalleguillos\User\Model\Entity\User as UserEntity;
use LeoGalleguillos\User\Model\Service\User as UserService;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    protected function setUp()
    {
        $this->userEntity            = new UserEntity();
        $this->userEntity->firstName = 'Leo';
        $this->userEntity->lastName  = 'Galleguillos';

        $this->userService = new UserService();
    }

    public function testInitialize()
    {
        $this->assertInstanceOf(UserService::class, $this->userService);
    }

    public function testGetFullName()
    {
        $this->assertSame(
            'Leo Galleguillos',
            $this->userService->getFullName($this->userEntity)
        );
    }
}
