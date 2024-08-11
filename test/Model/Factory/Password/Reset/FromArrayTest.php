<?php
namespace MonthlyBasis\UserTest\Model\Factory\Password\Reset;

use DateTime;
use MonthlyBasis\User\Model\Entity as UserEntity;
use MonthlyBasis\User\Model\Factory as UserFactory;
use PHPUnit\Framework\TestCase;

class FromArrayTest extends TestCase
{
    protected function setUp(): void
    {
        $this->fromArrayFactory = new UserFactory\Password\Reset\FromArray();
    }

    public function test_buildFromArray_setSomeProperties_somePropertiesAreSet()
    {
        $resetEntity = (new UserEntity\Password\Reset())
            ->setCode('the-code')
            ->setCreated(new DateTime('2021-06-23 19:32:32'))
            ->setResetId(7)
            ->setUserId(12345)
            ;
        $this->assertEquals(
            $resetEntity,
            $this->fromArrayFactory->buildFromArray([
                'code' => 'the-code',
                'created' => '2021-06-23 19:32:32',
                'reset_password_id' => '7',
                'user_id' => '12345',
            ])
        );
    }

    public function test_buildFromArray_setAllProperties_allPropertiesAreSet()
    {
        $resetEntity = (new UserEntity\Password\Reset())
            ->setAccessed(new DateTime('2021-06-23 23:39:39'))
            ->setCode('the-code')
            ->setCreated(new DateTime('2021-06-23 19:32:32'))
            ->setResetId(7)
            ->setUserId(12345)
            ->setUsed(new DateTime('2021-06-23 23:40:40'))
            ;
        $this->assertEquals(
            $resetEntity,
            $this->fromArrayFactory->buildFromArray([
                'accessed' => '2021-06-23 23:39:39',
                'code' => 'the-code',
                'created' => '2021-06-23 19:32:32',
                'reset_password_id' => '7',
                'user_id' => '12345',
                'used' => '2021-06-23 23:40:40',
            ])
        );
    }
}
