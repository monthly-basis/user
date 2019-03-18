<?php
namespace LeoGalleguillos\UserTest\Model\Service;

use LeoGalleguillos\User\Model\Entity as UserEntity;
use LeoGalleguillos\User\Model\Service as UserService;
use LeoGalleguillos\User\Model\Table as UserTable;
use PHPUnit\Framework\TestCase;

class DisplayNameOrUsernameTest extends TestCase
{
    protected function setUp()
    {
        $this->displayNameOrUsernameService = new UserService\DisplayNameOrUsername();
    }

    public function testGetDisplayNameOrUsername()
    {
        $userEntity = new UserEntity\User();
        $userEntity->setUsername('Foo');

        $this->assertSame(
            'Foo',
            $this->displayNameOrUsernameService->getDisplayNameOrUsername($userEntity)
        );

        $userEntity->setDisplayName('Bar');

        $this->assertSame(
            'Bar',
            $this->displayNameOrUsernameService->getDisplayNameOrUsername($userEntity)
        );
    }
}
