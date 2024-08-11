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
