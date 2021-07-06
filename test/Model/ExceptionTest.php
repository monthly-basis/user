<?php
namespace MonthlyBasis\UserTest\Model;

use MonthlyBasis\User\Model\Exception as UserException;
use PHPUnit\Framework\TestCase;

class ExceptionTest extends TestCase
{
    protected function setUp(): void
    {
        $this->exception = new UserException();
    }

    public function test_123()
    {
        $this->assertTrue(true);

        $this->assertInstanceOf(
            \Exception::class,
            $this->exception
        );
    }
}
