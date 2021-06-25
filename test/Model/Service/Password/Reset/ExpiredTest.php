<?php
namespace MonthlyBasis\UserTest\Model\Service\Password\Reset;

use DateTime;
use MonthlyBasis\User\Model\Entity as UserEntity;
use MonthlyBasis\User\Model\Service as UserService;
use PHPUnit\Framework\TestCase;

class ExpiredTest extends TestCase
{
    protected function setUp(): void
    {
        $this->expiredService = new UserService\Password\Reset\Expired();
    }

    public function test_isExpired()
    {
        $resetEntity = new UserEntity\Password\Reset();

        $dateTimeTwoDaysAgo = (new DateTime())
            ->modify('-2 days');
        $resetEntity->setCreated($dateTimeTwoDaysAgo);
        $this->assertTrue(
            $this->expiredService->isExpired($resetEntity)
        );

        $dateTimeEighteenHoursAgo = (new DateTime())
            ->modify('-18 hours');
        $resetEntity->setCreated($dateTimeEighteenHoursAgo);
        $this->assertFalse(
            $this->expiredService->isExpired($resetEntity)
        );

        $resetEntity->setCreated(new DateTime());
        $this->assertFalse(
            $this->expiredService->isExpired($resetEntity)
        );

        $dateTimeTenMinutesAgo = (new DateTime())
            ->modify('-10 minutes');
        $resetEntity->setAccessed($dateTimeTenMinutesAgo);
        $this->assertTrue(
            $this->expiredService->isExpired($resetEntity)
        );

        $dateTimeThreeMinutesAgo = (new DateTime())
            ->modify('-3 minutes');
        $resetEntity->setAccessed($dateTimeThreeMinutesAgo);
        $this->assertFalse(
            $this->expiredService->isExpired($resetEntity)
        );

        $resetEntity->setUsed(new DateTime());
        $this->assertTrue(
            $this->expiredService->isExpired($resetEntity)
        );
    }
}
