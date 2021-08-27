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

    public function updateSetLoginHashHttpsTokenWhereUserId(
        string $loginHash,
        string $httpsToken,
        int $userId
    ): Result {
        $update = $this->sql
            ->update('user')
            ->set([
               'login_hash'  => $loginHash,
               'https_token' => $httpsToken,
            ])
            ->where([
               'user_id' => $userId,
            ])
            ;
        return $this->sql->prepareStatementForSqlObject($update)->execute();
    }

    public function updateSetLoginIpWhereUserId(
        string $loginIp,
        int $userId
    ): Result {
        $update = $this->sql
            ->update('user')
            ->set([
               'login_ip' => $loginIp,
            ])
            ->where([
               'user_id' => $userId,
            ])
            ;
        return $this->sql->prepareStatementForSqlObject($update)->execute();
    }
}
