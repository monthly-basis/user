<?php
namespace MonthlyBasis\User\Model\Table;

use Laminas\Db\Adapter\Driver\Pdo\Result;
use MonthlyBasis\User\Model\Db as UserDb;

class RegisterNotOldEnoughLog
{
    public function __construct(UserDb\Sql $sql)
    {
        $this->sql = $sql;
    }

    public function insert(
        string $ipAddress
    ): Result {
        $insert = $this->sql
             ->insert()
             ->into('register_not_old_enough_log')
             ->values([
                 'ip_address' => $ipAddress,
             ])
             ;
        return $this->sql->prepareStatementForSqlObject($insert)->execute();
    }
}
