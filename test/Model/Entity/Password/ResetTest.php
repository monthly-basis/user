<?php
namespace MonthlyBasis\UserTest\Model\Entity\Password;

use DateTime;
use MonthlyBasis\User\Model\Entity as UserEntity;
use PHPUnit\Framework\TestCase;

class ResetTest extends TestCase
{
    protected function setUp(): void
    {
        $this->resetEntity = new UserEntity\Password\Reset();
    }

    public function test_gettersAndSetters()
    {
        $accessed = new DateTime();
        $this->assertSame(
            $this->resetEntity,
            $this->resetEntity->setAccessed($accessed)
        );
        $this->assertSame(
            $accessed,
            $this->resetEntity->getAccessed()
        );

        $created = new DateTime();
        $this->assertSame(
            $this->resetEntity,
            $this->resetEntity->setCreated($created)
        );
        $this->assertSame(
            $created,
            $this->resetEntity->getCreated()
        );

        $code = 'this is the code';
        $this->assertSame(
            $this->resetEntity,
            $this->resetEntity->setCode($code)
        );
        $this->assertSame(
            $code,
            $this->resetEntity->getCode()
        );

        $resetId = 123;
        $this->assertSame(
            $this->resetEntity,
            $this->resetEntity->setResetId($resetId)
        );
        $this->assertSame(
            $resetId,
            $this->resetEntity->getResetId()
        );

        $used = new DateTime();
        $this->assertSame(
            $this->resetEntity,
            $this->resetEntity->setUsed($used)
        );
        $this->assertSame(
            $used,
            $this->resetEntity->getUsed()
        );

        $userId = 123;
        $this->assertSame(
            $this->resetEntity,
            $this->resetEntity->setUserId($userId)
        );
        $this->assertSame(
            $userId,
            $this->resetEntity->getUserId()
        );
    }
}
