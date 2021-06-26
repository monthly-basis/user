<?php
namespace MonthlyBasis\UserTest\Model\Service\Password\Reset\Accessed;

use MonthlyBasis\User\Model\Entity as UserEntity;
use MonthlyBasis\User\Model\Service as UserService;
use MonthlyBasis\User\Model\Table as UserTable;
use PHPUnit\Framework\TestCase;

class ConditionallyUpdateTest extends TestCase
{
    protected function setUp(): void
    {
        $this->resetPasswordTableMock = $this->createMock(
            UserTable\ResetPassword::class
        );
        $this->conditionallyUpdateService = new UserService\Password\Reset\Accessed\ConditionallyUpdate(
            $this->resetPasswordTableMock,
        );
    }

    public function test_conditionallyUpdateAccessed_accessedIsNotSet_accessedGetsSet()
    {
        $this->resetPasswordTableMock
             ->expects($this->once())
             ->method('updateSetAccessedToUtcTimestampWhereUserIdAndCode')
             ->with(12345, 'the-code')
             ;

        $resetEntity = (new UserEntity\Password\Reset())
            ->setCode('the-code')
            ->setUserId(12345)
            ;
        $this->conditionallyUpdateService->conditionallyUpdateAccessed(
            $resetEntity
        );
    }

    public function test_conditionallyUpdateAccessed_accessedIsSet_accessedDoesNotGetSet()
    {
        $this->resetPasswordTableMock
             ->expects($this->exactly(0))
             ->method('updateSetAccessedToUtcTimestampWhereUserIdAndCode')
             ;

        $resetEntity = (new UserEntity\Password\Reset())
            ->setAccessed(new \DateTime())
            ->setCode('the-code')
            ->setUserId(12345)
            ;
        $this->conditionallyUpdateService->conditionallyUpdateAccessed(
            $resetEntity
        );
    }
}
