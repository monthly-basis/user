<?php
namespace MonthlyBasis\User\Model\Table;

use Exception;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\Driver\Pdo\Result;

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

    public function selectWhereUserIdAndCode(
        int $userId,
        string $code
    ): Result {
        $sql = '
            SELECT `reset_password`.`reset_password_id`
                 , `reset_password`.`user_id`
                 , `reset_password`.`code`
                 , `reset_password`.`created`
                 , `reset_password`.`accessed`
                 , `reset_password`.`used`
              FROM `reset_password`
             WHERE `reset_password`.`user_id` = ?
               AND `reset_password`.`code` = ?
                 ;
        ';
        $parameters = [
            $userId,
            $code,
        ];
        return $this->adapter->query($sql)->execute($parameters);
    }

    public function updateSetAccessedToUtcTimestampWhereUserIdAndCode(
        int $userId,
        string $code
    ): Result {
        $sql = '
            UPDATE `reset_password`
               SET `reset_password`.`accessed` = UTC_TIMESTAMP()
             WHERE `reset_password`.`user_id` = ?
               AND `reset_password`.`code` = ?
                 ;
        ';
        $parameters = [
            $userId,
            $code,
        ];
        return $this->adapter->query($sql)->execute($parameters);
    }

    public function updateSetUsedToUtcTimestampWhereUserIdAndCode(
        int $userId,
        string $code
    ): Result {
        $sql = '
            UPDATE `reset_password`
               SET `reset_password`.`used` = UTC_TIMESTAMP()
             WHERE `reset_password`.`user_id` = ?
               AND `reset_password`.`code` = ?
                 ;
        ';
        $parameters = [
            $userId,
            $code,
        ];
        return $this->adapter->query($sql)->execute($parameters);
    }
}
