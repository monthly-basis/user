<?php
namespace MonthlyBasis\UserTest\Model\Table;

use MonthlyBasis\LaminasTest\TableTestCase;
use MonthlyBasis\User\Model\Table as UserTable;
use MonthlyBasis\User\Model\Db as UserDb;

class LoginTokenTest extends TableTestCase
{
    protected function setUp(): void
    {
        $this->sql = new UserDb\Sql(
            $this->getAdapter()
        );

        $this->loginTokenTable = new UserTable\LoginToken($this->sql);

        $this->dropAndCreateTable('login_token');
    }

    public function test_insert()
    {
        $result = $this->loginTokenTable->insert([
            'login_token' => 'the-login-token',
            'login_ip'    => '1.2.3.4',
            'user_id'     => 12345,
            'created'     => '2021-10-19 07:30:13',
            'expires'     => '2021-01-19 07:30:13',
        ]);

        $this->assertSame(
            '1',
            $result->getGeneratedValue()
        );
        $this->assertSame(
            1,
            $result->getAffectedRows()
        );
    }
}
