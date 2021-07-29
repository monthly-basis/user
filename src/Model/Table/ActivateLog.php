<?php
namespace MonthlyBasis\User\Model\Table;

use Laminas\Db\Adapter\Driver\Pdo\Result;
use Laminas\Db\Sql\Expression;
use MonthlyBasis\User\Model\Db as UserDb;

class ActivateLog
{
    public function __construct(UserDb\Sql $sql)
    {
        $this->sql = $sql;
    }

    public function insert(
        string $ipAddress,
        bool $success
    ): Result {
        $insert = $this->sql
             ->insert()
             ->into('activate_log')
             ->values([
                 'ip_address' => $ipAddress,
                 'success'    => intval($success),
             ])
             ;
        return $this->sql->prepareStatementForSqlObject($insert)->execute();
    }

    public function selectCountWhereIpAddressAndSuccess(
        string $ipAddress,
        bool $success
    ): Result {
        $select = $this->sql
            ->select()
            ->columns([
                'COUNT(*)' => new Expression('COUNT(*)'),
            ])
            ->from('activate_log')
            ->where([
                'ip_address' => $ipAddress,
                 'success'   => intval($success),
            ])
            ;
        return $this->sql->prepareStatementForSqlObject($select)->execute();
    }
}
