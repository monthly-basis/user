<?php
namespace LeoGalleguillos\User\Model\Table;

use Exception;
use Zend\Db\Adapter\Adapter;

class ResetPassword
{
    /**
     * @var Adapter
     */
    protected $adapter;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @return int
     */
    public function insert(
        int $userId,
        string $code
    ) {
        $sql = '
            INSERT
              INTO `reset_password` (`user_id`, `code`, `created`)
            VALUES (?, ?, UTC_TIMESTAMP())
                 ;
        ';
        $parameters = [
            $userId,
            $code,
        ];
        return $this->adapter
                    ->query($sql)
                    ->execute($parameters)
                    ->getGeneratedValue();
    }

    public function selectCountWhereUserIdAndCreatedGreaterThan(
        int $userId,
        string $created
    ): int {
        $sql = '
            SELECT COUNT(*) AS `count`
              FROM `reset_password`
             WHERE `reset_password`.`user_id` = ?
               AND `reset_password`.`created` > ?
                 ;
        ';
        $parameters = [
            $userId,
            $created,
        ];
        return (int) $this->adapter
                          ->query($sql)
                          ->execute($parameters)
                          ->current()['count'];
    }

    public function selectUserIdWhereCode(string $code): int
    {
        $sql = '
            SELECT `reset_password`.`user_id`
              FROM `reset_password`
             WHERE `reset_password`.`code` = ?
                 ;
        ';
        $parameters = [
            $code,
        ];
        $userId = (int) $this->adapter
                            ->query($sql)
                            ->execute($parameters)
                            ->current()['user_id'];

        if (empty($userId)) {
            throw new Exception('User ID where code not found.');
        }

        return $userId;
    }
}
