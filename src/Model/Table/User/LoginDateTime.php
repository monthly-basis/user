<?php
namespace MonthlyBasis\User\Model\Table\User;

use Laminas\Db\Adapter\Adapter;

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

    public function updateSetToNowWhereUserId(int $userId)
    {
        $sql = '
            UPDATE `user`
               SET `user`.`login_datetime` = UTC_TIMESTAMP()
             WHERE `user`.`user_id` = :userId
                 ;
        ';
        $parameters = [
            'userId'        => $userId,
        ];
        return (bool) $this->adapter
                           ->query($sql)
                           ->execute($parameters)
                           ->getAffectedRows();
    }
}
