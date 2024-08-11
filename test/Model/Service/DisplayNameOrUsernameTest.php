<?php
namespace MonthlyBasis\UserTest\Model\Service;

use MonthlyBasis\User\Model\Entity as UserEntity;
use MonthlyBasis\User\Model\Service as UserService;
use MonthlyBasis\User\Model\Table as UserTable;
use PHPUnit\Framework\TestCase;

class DisplayNameOrUsernameTest extends TestCase
{
    protected function setUp(): void
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
