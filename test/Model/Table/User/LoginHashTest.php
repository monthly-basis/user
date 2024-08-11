<?php
namespace MonthlyBasis\UserTest\Model\Table\User;

use Laminas\Db\Adapter\Adapter;
use MonthlyBasis\LaminasTest\TableTestCase;
use MonthlyBasis\User\Model\Table as UserTable;

class LoginHashTest extends TableTestCase
{
    protected function setUp(): void
    {
        $this->userTable = new UserTable\User($this->getAdapter());
        $this->loginHashTable = new UserTable\User\LoginHash($this->getAdapter());

        $this->setForeignKeyChecks0();
        $this->dropAndCreateTable('user');
        $this->setForeignKeyChecks1();
    }
}
