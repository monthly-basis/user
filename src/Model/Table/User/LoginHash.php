<?php
namespace MonthlyBasis\User\Model\Table\User;

use Laminas\Db\Adapter\Adapter;

class LoginHash
{
    /**
     * @var Adapter
     */
    private $adapter;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    public function updateWhereUserId(string $loginHash, int $userId)
    {
        $sql = '
            UPDATE `user`
               SET `user`.`login_hash` = :loginHash
             WHERE `user`.`user_id` = :userId
                 ;
        ';
        $parameters = [
            'loginHash' => $loginHash,
            'userId'    => $userId,
        ];
        return (bool) $this->adapter
                           ->query($sql)
                           ->execute($parameters)
                           ->getAffectedRows();
    }

    public function updateWhereUsername(string $loginHash, string $username)
    {
        $sql = '
            UPDATE `user`
               SET `user`.`login_hash` = :loginHash
             WHERE `user`.`username` = :username
                 ;
        ';
        $parameters = [
            'loginHash' => $loginHash,
            'username'  => $username,
        ];
        return (bool) $this->adapter
                           ->query($sql)
                           ->execute($parameters)
                           ->getAffectedRows();
    }
}
