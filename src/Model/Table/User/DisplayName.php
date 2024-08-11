<?php
namespace LeoGalleguillos\User\Model\Table\User;

use Zend\Db\Adapter\Adapter;

class DisplayName
{
    /**
     * @var Adapter
     */
    private $adapter;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    public function updateWhereUserId(string $displayName, int $userId)
    {
        $sql = '
            UPDATE `user`
               SET `user`.`display_name` = :displayName
             WHERE `user`.`user_id` = :userId
                 ;
        ';
        $parameters = [
            'displayName' => $displayName,
            'userId'      => $userId,
        ];
        return (bool) $this->adapter
                           ->query($sql)
                           ->execute($parameters)
                           ->getAffectedRows();
    }
}
