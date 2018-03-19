<?php
namespace LeoGalleguillos\User\Model\Table\User;

use Zend\Db\Adapter\Adapter;

class LoginDateTime
{
    /**
     * @var Adapter
     */
    private $adapter;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    public function updateWhereUserId(string $loginDateTime, int $userId)
    {
        $sql = '
            UPDATE `user`
               SET `user`.`login_datetime` = :loginDateTime
             WHERE `user`.`user_id` = :userId
                 ;
        ';
        $parameters = [
            'loginDateTime' => $loginDateTime,
            'userId'        => $userId,
        ];
        return (bool) $this->adapter
                           ->query($sql)
                           ->execute($parameters)
                           ->getAffectedRows();
    }

    public function updateWhereUsername(string $loginDateTime, string $username)
    {
        $sql = '
            UPDATE `user`
               SET `user`.`login_datetime` = :loginDateTime
             WHERE `user`.`username` = :username
                 ;
        ';
        $parameters = [
            'loginDateTime' => $loginDateTime,
            'username'      => $username,
        ];
        return (bool) $this->adapter
                           ->query($sql)
                           ->execute($parameters)
                           ->getAffectedRows();
    }
}
