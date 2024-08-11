<?php
namespace MonthlyBasis\User\Model\Table;

use DateTime;
use Laminas\Db\Adapter\Driver\Pdo\Result;
use MonthlyBasis\User\Model\Db as UserDb;
use MonthlyBasis\User\Model\Table as UserTable;

class UserUserToken
{
    protected UserDb\Sql $sql;

    public function __construct(
        UserDb\Sql $sql,
        UserTable\User $userTable,
        UserTable\UserToken $userTokenTable
    ) {
        $this->sql            = $sql;
        $this->userTable      = $userTable;
        $this->userTokenTable = $userTokenTable;
    }
}
