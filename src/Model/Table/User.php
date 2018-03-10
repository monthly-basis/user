<?php
namespace LeoGalleguillos\User\Model\Table;

use ArrayObject;
use Exception;
use Generator;
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

    public function selectOrderByCreatedDesc() : Generator
    {
        $sql = '
            SELECT `user_id`
                 , `username`
                 , `password_hash`
                 , `welcome_message`
                 , `views`
                 , `created`
              FROM `user`
             ORDER
                BY `created` DESC
             LIMIT 100
                 ;
        ';
        foreach ($this->adapter->query($sql)->execute() as $row) {
            yield($row);
        }
    }

    /**
     * @deprecated If needed, write a new selectWhereUsernameOrEmail method instead
     */
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

    public function selectWhereUserId(int $userId) : array
    {
        $sql = '
            SELECT `user_id`
                 , `username`
                 , `password_hash`
                 , `welcome_message`
                 , `views`
                 , `created`
              FROM `user`
             WHERE `user_id` = ?
                 ;
        ';
        return $this->adapter->query($sql)->execute([$userId])->current();
    }

    public function selectWhereUserIdLoginHashLoginIp(
        int $userId,
        string $loginHash,
        string $loginIp
    ) : array {
        $sql = '
            SELECT `user_id`
                 , `username`
                 , `password_hash`
                 , `welcome_message`
                 , `views`
                 , `created`
              FROM `user`
             WHERE `user_id` = :userId
               AND `login_hash` = :loginHash
               AND `login_ip` = :loginIp
                 ;
        ';
        $parameters = [
            'userId'    => $userId,
            'loginHash' => $loginHash,
            'loginIp'   => $loginIp,
        ];
        $result = $this->adapter
                       ->query($sql)
                       ->execute($parameters)
                       ->current();
        if (empty($result)) {
            throw new Exception('Row with user ID, login hash, and login IP not found.');
        }
        return $result;
    }

    public function selectWhereUsername(string $username)
    {
        $sql = '
            SELECT `user_id`
                 , `username`
                 , `password_hash`
                 , `welcome_message`
                 , `views`
                 , `created`
              FROM `user`
             WHERE `username` = ?
                 ;
        ';
        $arrayObject = $this->adapter->query($sql, [$username])->current();
        return $arrayObject;
    }

    public function updateViewsWhereUserId(int $userId) : bool
    {
        $sql = '
            UPDATE `user`
               SET `user`.`views` = `user`.`views` + 1
             WHERE `user`.`user_id` = ?
                 ;
        ';
        $parameters = [
            $userId
        ];
        return (bool) $this->adapter->query($sql, $parameters)->getAffectedRows();
    }

    public function updateWhereUserId(ArrayObject $arrayObject, int $userId) : bool
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
