<?php
namespace MonthlyBasis\UserTest\Model\Service\Password;

use MonthlyBasis\User\Model\Entity as UserEntity;
use MonthlyBasis\User\Model\Service as UserService;
use MonthlyBasis\User\Model\Table as UserTable;
use PHPUnit\Framework\TestCase;

class ChangeTest extends TestCase
{
    protected function setUp(): void
    {
        $this->userIdTableMock = $this->createMock(
            UserTable\User\UserId::class
        );

        $this->changeService = new UserService\Password\Change(
            $this->userIdTableMock
        );
    }

    public function test_changePassword()
    {
        $this->userIdTableMock
             ->expects($this->once())
             ->method('updateSetPasswordHashWhereUserId')
             ->with(
                 $this->isType('string'),
                 2718
             )
             ;
        $userEntity = (new UserEntity\User())
            ->setUserId(2718)
            ;

        $this->changeService->changePassword(
            $userEntity,
            'the new password'
        );
    }
}
