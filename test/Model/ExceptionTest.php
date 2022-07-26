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

    public function test_instance_expectedBehavior()
    {
        $this->assertInstanceOf(
            \Exception::class,
            $this->exception
        );
    }
}
