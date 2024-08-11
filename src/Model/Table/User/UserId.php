<?php
namespace MonthlyBasis\User\Model\Table\User;

use Laminas\Db\Adapter\Driver\Pdo\Result;
use MonthlyBasis\User\Model\Db as UserDb;

class UserId
{
    protected UserDb\Sql $sql;

    public function __construct(UserDb\Sql $sql)
    {
        $this->sql = $sql;
    }

    public function updateSetPasswordHashWhereUserId(
        string $passwordHash,
        int $userId
    ): Result {
        $update = $this->sql
            ->update('user')
            ->set([
               'password_hash'  => $passwordHash,
            ])
            ->where([
               'user_id' => $userId,
            ])
            ;
        return $this->sql->prepareStatementForSqlObject($update)->execute();
    }
}
