<?php
namespace LeoGalleguillos\User\Model\Table\User;

use Zend\Db\Adapter\Adapter;

class LoginIp
{
    /**
     * @var Adapter
     */
    private $adapter;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    public function updateWhereUserId(string $loginIp, int $userId)
    {
        $sql = '
            UPDATE `user`
               SET `user`.`login_ip` = :loginIp
             WHERE `user`.`user_id` = :userId
                 ;
        ';
        $parameters = [
            'loginIp'  => $loginIp,
            'userId'   => $userId,
        ];
        return (bool) $this->adapter
                           ->query($sql)
                           ->execute($parameters)
                           ->getAffectedRows();
    }

    public function updateWhereUsername(string $loginIp, string $username)
    {
        $sql = '
            UPDATE `user`
               SET `user`.`login_ip` = :loginIp
             WHERE `user`.`username` = :username
                 ;
        ';
        $parameters = [
            'loginIp'  => $loginIp,
            'username' => $username,
        ];
        return (bool) $this->adapter
                           ->query($sql)
                           ->execute($parameters)
                           ->getAffectedRows();
    }
}
