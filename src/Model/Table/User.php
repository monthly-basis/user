<?php
namespace LeoGalleguillos\User\Model\Table;

use ArrayObject;
use Zend\Db\Adapter\Adapter;

class User
{
    /**
     * @var Adapter
     */
    private $adapter;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    public function beginTransaction()
    {
        $this->adapter->getDriver()->getConnection()->beginTransaction();
    }

    public function commit()
    {
        $this->adapter->getDriver()->getConnection()->commit();
    }

    public function insert(
        $username,
        $passwordHash
    ) {
        $sql = '
            INSERT
              INTO `user` (`username`, `password_hash`, `created`)
            VALUES (?, ?, UTC_TIMESTAMP())
                 ;
        ';
        $parameters = [
            $username,
            $passwordHash
        ];
        return $this->adapter
                    ->query($sql, $parameters)
                    ->getGeneratedValue();
    }

    public function isUsernameInTable($username)
    {
        $sql = '
            SELECT COUNT(*) AS `count`
              FROM `user`
             WHERE `username` = ?
                 ;
        ';
        $row = $this->adapter->query($sql, [$username])->current();
        return (bool) $row['count'];
    }

    public function rollback()
    {
        $this->adapter->getDriver()->getConnection()->rollback();
    }

    public function selectCount()
    {
        $sql = '
            SELECT COUNT(*) AS `count`
              FROM `user`
                 ;
        ';
        $row = $this->adapter->query($sql)->execute()->current();
        return (int) $row['count'];
    }

    public function selectRow($usernameOrEmail)
    {
        $sql = '
            SELECT `user`.`user_id`
                 , `user`.`username`
                 , `user`.`password_hash`
              FROM `user`
              JOIN `user_email`
             USING (`user_id`)
             WHERE `user`.`username` = ?
                OR `user_email`.`address` = ?
             LIMIT 1
                 ;
        ';
        $parameters = [
            $usernameOrEmail,
            $usernameOrEmail
        ];
        $row = $this->adapter->query($sql, $parameters)->current();

        if (empty($row)) {
            return false;
        }

        return (array) $row;
    }

    public function selectWhereUserId($userId)
    {
        $sql = '
            SELECT `user_id`
                 , `username`
                 , `password_hash`
                 , `welcome_message`
              FROM `user`
             WHERE `user_id` = ?
                 ;
        ';
        $row = $this->adapter->query($sql, [$userId])->current();

        return $row ? (array) $row : false;
    }

    public function selectWhereUsername(string $username) : ArrayObject
    {
        $sql = '
            SELECT `user_id`
                 , `username`
                 , `password_hash`
                 , `welcome_message`
              FROM `user`
             WHERE `username` = ?
                 ;
        ';
        $row = $this->adapter->query($sql, [$username])->current();

        return $row ? $row : false;
    }

    public function updateWhereUserId(ArrayObject $arrayObject, int $userId)
    {
        $sql = '
            UPDATE `user`
               SET `user`.`welcome_message` = ?
             WHERE `user`.`user_id` = ?
                 ;
        ';
        $parameters = [
            $arrayObject['welcome_message'],
            $userId
        ];
        return (bool) $this->adapter->query($sql, $parameters)->getAffectedRows();
    }
}
