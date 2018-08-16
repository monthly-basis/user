<?php
namespace LeoGalleguillos\User\Model\Table\User;

use Zend\Db\Adapter\Adapter;

class Username
{
    /**
     * @var Adapter
     */
    protected $adapter;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    public function updateWhereUserId(string $username, int $userId)
    {
        $sql = '
            UPDATE `user`
               SET `user`.`username` = ?
             WHERE `user`.`user_id` = ?
                 ;
        ';
        $parameters = [
            $username,
            $userId,
        ];
        return (bool) $this->adapter
                           ->query($sql)
                           ->execute($parameters)
                           ->getAffectedRows();
    }
}
