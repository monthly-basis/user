<?php
namespace MonthlyBasis\UserTest\Model\Service\Password\Reset;

use MonthlyBasis\User\Model\Service as UserService;
use PHPUnit\Framework\TestCase;

class GenerateCodeTest extends TestCase
{
    protected function setUp(): void
    {
        $this->generateCodeService = new UserService\Password\Reset\GenerateCode(
        );
    }

    public function test_generateCode_string()
    {
        $string = $this->generateCodeService->generateCode();
        $this->assertSame(
            32,
            strlen($string)
        );
    }
}
