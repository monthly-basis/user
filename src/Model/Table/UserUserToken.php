<?php
namespace MonthlyBasis\User\Model\Table;

use DateTime;
use Laminas\Db\Adapter\Driver\Pdo\Result;
use Laminas\Db\Sql\Predicate\Operator;
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

    public function selectWhereUserIdLoginTokenExpiresDeleted(
        int $userId,
        string $loginToken
    ): Result {
        $select = $this->sql
            ->select()
            ->columns($this->userTable->getColumns())
            ->from('user')
            ->join(
                'user_token',
                'user.user_id = user_token.user_id',
                $this->userTokenTable->getColumns()
            );
        $where = (new \Laminas\Db\Sql\Where())
            ->equalTo('user.user_id', $userId)
            ->equalTo('user_token.login_token', $loginToken)
            ->greaterThan(
                'user_token.expires',
                new \Laminas\Db\Sql\Expression('UTC_TIMESTAMP()'),
                Operator::TYPE_IDENTIFIER,
                Operator::TYPE_IDENTIFIER
            )
            ->isNull('user_token.deleted')
            ;
        $select->where($where);

        return $this->sql->prepareStatementForSqlObject($select)->execute();
    }
}
