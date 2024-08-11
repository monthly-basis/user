<?php
namespace LeoGalleguillos\User\Model\Table;

use Exception;
use Zend\Db\Adapter\Adapter;

class ResetPasswordAccessLog
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
        string $ip,
        int $valid
    ): int {
        $sql = '
            INSERT
              INTO `reset_password_access_log` (`ip`, `valid`, `created`)
            VALUES (?, ?, UTC_TIMESTAMP())
                 ;
        ';
        $parameters = [
            $ip,
            $valid,
        ];
        return $this->adapter
                    ->query($sql)
                    ->execute($parameters)
                    ->getGeneratedValue();
    }

    public function selectCountWhereUserIdAndValidAndCreatedGreaterThan(
        string $ip,
        int $valid,
        string $created
    ): int {
        $sql = '
            SELECT COUNT(*) AS `count`
              FROM `reset_password_access_log`
             WHERE `reset_password_access_log`.`ip` = ?
               AND `reset_password_access_log`.`valid` = ?
               AND `reset_password_access_log`.`created` > ?
                 ;
        ';
        $parameters = [
            $ip,
            $valid,
            $created,
        ];
        return (int) $this->adapter
                          ->query($sql)
                          ->execute($parameters)
                          ->current()['count'];
    }
}
