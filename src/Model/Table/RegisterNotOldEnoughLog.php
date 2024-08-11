<?php
namespace MonthlyBasis\User\Model\Table;

use DateTime;
use Laminas\Db\Adapter\Driver\Pdo\Result;
use Laminas\Db\Sql\Predicate\Operator;
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

    public function selectWhereIpAddressAndCreatedGreaterThan(
        string $ipAddress,
        DateTime $created
    ): Result {
        $select = $this->sql
            ->select()
            ->columns([
                'register_not_old_enough_log_id',
                'ip_address',
                'created',
            ])
            ->from('register_not_old_enough_log')
            ;
        $select->where
            ->equalTo('ip_address', $ipAddress)
            ->greaterThanOrEqualTo('created', $created->format('Y-m-d H:i:s'))
            ;
        return $this->sql->prepareStatementForSqlObject($select)->execute();
    }
}
