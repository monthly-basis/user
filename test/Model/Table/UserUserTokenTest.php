<?php
namespace MonthlyBasis\UserTest\Model\Table;

use DateTime;
use Laminas\Db\Adapter\Driver\Pdo\Result;
use MonthlyBasis\LaminasTest\TableTestCase;
use MonthlyBasis\User\Model\Table as UserTable;
use MonthlyBasis\User\Model\Db as UserDb;

class UserUserTokenTest extends TableTestCase
{
    protected function setUp(): void
    {
        $this->sql = new UserDb\Sql(
            $this->getAdapter()
        );

        $this->userTable          = new UserTable\User($this->sql);
        $this->userTokenTable     = new UserTable\UserToken($this->sql);

        $this->userUserTokenTable = new UserTable\UserUserToken(
            $this->sql,
            $this->userTable,
            $this->userTokenTable
        );

        $this->setForeignKeyChecks(0);
        $this->dropAndCreateTables(['user', 'user_token']);
        $this->setForeignKeyChecks(1);
    }

    public function test_selectWhereUserIdLoginTokenExpiresDeleted_emptyResultDueToEmptyTables()
    {
        $result = $this->userUserTokenTable->selectWhereUserIdLoginTokenExpiresDeleted(
            1,
            'the-login-token'
        );
        $this->assertEmpty($result);
    }

    public function test_selectWhereUserIdLoginTokenExpiresDeleted_emptyResultDueToExpiredToken()
    {
        $this->userTable->insert(
            'username',
            'the-password-hash',
            '1983-10-22 00:00:00',
        );
        $this->userTokenTable->insert(
            1,
            'the-login-token',
            'the-https-token',
            (new \DateTime())->modify('-30 days'),
        );

        $result = $this->userUserTokenTable->selectWhereUserIdLoginTokenExpiresDeleted(
            1,
            'the-login-token'
        );
        $this->assertEmpty($result);
    }

    public function test_selectWhereUserIdLoginTokenExpiresDeleted_nonEmptyResult()
    {
        $this->userTable->insert(
            'username',
            'the-password-hash',
            '1983-10-22 00:00:00',
        );
        $this->userTokenTable->insert(
            1,
            'the-login-token',
            'the-https-token',
            (new \DateTime())->modify('+30 days'),
        );

        $result = $this->userUserTokenTable->selectWhereUserIdLoginTokenExpiresDeleted(
            1,
            'the-login-token'
        );
        $array = $result->current();

        $this->assertSame(
            [
                'user_id'       => '1',
                'username'      => 'username',
                'password_hash' => 'the-password-hash',
                'login_token'   => 'the-login-token',
                'https_token'   => 'the-https-token',
            ],
            [
                'user_id'       => $array['user_id'],
                'username'      => $array['username'],
                'password_hash' => $array['password_hash'],
                'login_token'   => $array['login_token'],
                'https_token'   => $array['https_token'],
            ]
        );
    }
}
