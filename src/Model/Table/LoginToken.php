<?php
namespace MonthlyBasis\User\Model\Table;

use Laminas\Db\Adapter\Driver\Pdo\Result;
use MonthlyBasis\User\Model\Db as UserDb;

class LoginToken
{
    protected UserDb\Sql $sql;

    public function __construct(UserDb\Sql $sql)
    {
        $this->sql = $sql;
    }

    public function insert(
        array $array
    ): Result {
        $insert = $this->sql
             ->insert()
             ->into('login_token')
             ->values($array)
             ;
        return $this->sql->prepareStatementForSqlObject($insert)->execute();
    }
}
