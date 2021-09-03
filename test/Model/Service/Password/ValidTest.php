<?php
namespace MonthlyBasis\UserTest\Model\Service\Password;

use MonthlyBasis\User\Model\Service as UserService;
use PHPUnit\Framework\TestCase;

class ValidTest extends TestCase
{
    protected function setUp(): void
    {
        $this->validService = new UserService\Password\Valid();
    }

    public function test_isValid()
    {
        $this->assertFalse(
            $this->validService->isValid(
                '',
                ''
            )
        );

        $this->assertFalse(
            $this->validService->isValid(
                'hello world',
                ''
            )
        );

        $this->assertFalse(
            $this->validService->isValid(
                'hello world',
                '$2y$10$invalidHash/ruTvJs8A3jC.QNBJ7WOrVemjeoZFTBDMqzVCyPVWv'
            )
        );

        $this->assertTrue(
            $this->validService->isValid(
                'hello world',
                '$2y$10$e2GaOLYMp/ruTvJs8A3jC.QNBJ7WOrVemjeoZFTBDMqzVCyPVWv1e'
            )
        );
    }
}
