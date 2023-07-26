<?php
namespace MonthlyBasis\UserTest\Model\Table;

use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\Driver\Pdo\Result;
use MonthlyBasis\LaminasTest\TableTestCase;
use MonthlyBasis\User\Model\Table as UserTable;

class UserFollowTest extends TableTestCase
{
    protected function setUp(): void
    {
        $this->dropAndCreateTable('user_follow');

        $this->sql = $this->getSql();

        $this->userFollowTable = new UserTable\UserFollow(
            $this->sql
        );
    }

    public function test_insert()
    {
        $result = $this->userFollowTable->insert([
            'user_id_1' => 1,
            'user_id_2' => 2,
        ]);

        $this->assertSame(
            1,
            $result->getAffectedRows()
        );
    }
}
