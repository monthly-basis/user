<?php
namespace LeoGalleguillos\User\Model\Table\User;

use Zend\Db\Adapter\Adapter;

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
