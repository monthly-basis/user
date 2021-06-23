<?php
namespace MonthlyBasis\UserTest\Model\Table;

use MonthlyBasis\User\Model\Table as UserTable;
use MonthlyBasis\LaminasTest\TableTestCase;

class ResetPasswordTest extends TableTestCase
{
    protected function setUp(): void
    {
        $this->dropAndCreateTable('reset_password');
        $this->resetPasswordTable = new UserTable\ResetPassword(
            $this->getAdapter()
        );
    }

    public function testInitialize()
    {
        $this->assertInstanceOf(
            UserTable\ResetPassword::class,
            $this->resetPasswordTable
        );
    }
}
