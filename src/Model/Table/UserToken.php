<?php
namespace MonthlyBasis\User\Model\Table;

use DateTime;
use Laminas\Db\Adapter\Driver\Pdo\Result;
use MonthlyBasis\User\Model\Db as UserDb;

class UserToken
{
    protected UserDb\Sql $sql;

    public function __construct(UserDb\Sql $sql)
    {
        $this->sql = $sql;
    }

    public function getColumns(): array
    {
        return [
            'user_token_id',
            'user_id',
            'login_token',
            'https_token',
            'created',
            'expires',
            'deleted',
        ];
    }

    public function insert(
        int $userId,
        string $loginToken,
        string $httpsToken,
        DateTime $expires
    ): Result {
        $insert = $this->sql
             ->insert()
             ->into('user_token')
             ->values([
                'user_id'     => $userId,
                'login_token' => $loginToken,
                'https_token' => $httpsToken,
                'expires'     => $expires->format('Y-m-d H:i:s'),
             ])
             ;
        return $this->sql->prepareStatementForSqlObject($insert)->execute();
    }
}
