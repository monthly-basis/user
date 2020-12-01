<?php
namespace MonthlyBasis\User\Model\Table;

use ArrayObject;
use Exception;
use Generator;
use TypeError;
use Laminas\Db\Adapter\Adapter;

class User
{
    /**
     * @var Adapter
     */
    protected $adapter;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    public function getSelect(): string
    {
        return '
            SELECT `user_id`
                 , `username`
                 , `password_hash`
                 , `gender`
                 , `display_name`
                 , `welcome_message`
                 , `views`
                 , `created`
                 , `deleted_datetime`
                 , `deleted_user_id`
                 , `deleted_reason`
        ';
    }

    /**
     * @return int
     */
    public function insert(
        string $username,
        string $passwordHash,
        string $birthday,
        string $gender = null
    ) {
        $sql = '
            INSERT
              INTO `user`
                   (
                       `username`
                     , `password_hash`
                     , `birthday`
                     , `gender`
                     , `created`
                   )
            VALUES (?, ?, ?, ?, UTC_TIMESTAMP())
                 ;
        ';
        $parameters = [
            $username,
            $passwordHash,
            $birthday,
            $gender,
        ];
        return $this->adapter
                    ->query($sql)
                    ->execute($parameters)
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

    /**
     * Select order by created desc.
     *
     * @return Generator
     * @yield array
     */
    public function selectOrderByCreatedDesc(): Generator
    {
        $sql = '
            SELECT `user_id`
                 , `username`
                 , `password_hash`
                 , `display_name`
                 , `welcome_message`
                 , `views`
                 , `created`
              FROM `user`
             ORDER
                BY `created` DESC
             LIMIT 100
                 ;
        ';
        foreach ($this->adapter->query($sql)->execute() as $array) {
            yield($array);
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
                 , `user`.`display_name`
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

    public function selectWhereUserId(int $userId): array
    {
        $sql = $this->getSelect()
             . '
              FROM `user`
             WHERE `user_id` = ?
                 ;
        ';
        return $this->adapter->query($sql)->execute([$userId])->current();
    }

    public function selectWhereUserIdLoginHash(
        int $userId,
        string $loginHash
    ) : array {
        $sql = $this->getSelect()
             . '
              FROM `user`
             WHERE `user_id` = :userId
               AND `login_hash` = :loginHash
                 ;
        ';
        $parameters = [
            'userId'    => $userId,
            'loginHash' => $loginHash,
        ];
        $result = $this->adapter
                       ->query($sql)
                       ->execute($parameters)
                       ->current();
        if (empty($result)) {
            throw new Exception('Row with user ID and login hash not found.');
        }
        return $result;
    }

    /**
     * @throws TypeError
     */
    public function selectWhereUsername(string $username): array
    {
        $sql = $this->getSelect()
             . '
              FROM `user`
             WHERE `username` = ?
                 ;
        ';
        $parameters = [
            $username,
        ];
        return $this->adapter->query($sql)->execute($parameters)->current();
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
