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
            'user_token_created' => 'created',
            'user_token_expires' => 'expires',
            'user_token_deleted' => 'deleted',
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

    public function updateSetDeletedWhereUserIdLoginToken(
        int $userId,
        string $loginToken
    ): Result {
        $update = $this->sql
             ->update()
             ->table('user_token')
             ->set([
                'deleted' => new \Laminas\Db\Sql\Expression('UTC_TIMESTAMP()'),
             ])
             ->where([
                'user_id'     => $userId,
                'login_token' => $loginToken,
             ])
             ;
        return $this->sql->prepareStatementForSqlObject($update)->execute();
    }
}
