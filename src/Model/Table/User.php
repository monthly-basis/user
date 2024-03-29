<?php
namespace MonthlyBasis\User\Model\Table;

use ArrayObject;
use Exception;
use Generator;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\Driver\Pdo\Result;
use MonthlyBasis\User\Model\Db as UserDb;

class User
{
    protected Adapter $adapter;
    protected UserDb\Sql $sql;

    public function __construct(UserDb\Sql $sql)
    {
        $this->sql     = $sql;
        $this->adapter = $this->sql->getAdapter();
    }

    public function getColumns(): array
    {
        return [
            'user_id',
            'username',
            'password_hash',
            'gender',
            'display_name',
            'welcome_message',
            'open_ai_role',
            'views',
            'created',
            'deleted_datetime',
            'deleted_user_id',
            'deleted_reason',
        ];
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
                 , `open_ai_role`
                 , `views`
                 , `created`
                 , `deleted_datetime`
                 , `deleted_user_id`
                 , `deleted_reason`
        ';
    }

    public function insert(
        string $username,
        string $passwordHash,
        string $birthday,
        string $gender = null
    ): int {
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

    public function selectWhereUserId(int $userId): Result
    {
        $sql = '
            SELECT `user_id`
                 , `emoji_12_id`
                 , `username`
                 , `password_hash`
                 , `gender`
                 , `display_name`
                 , `welcome_message`
                 , `open_ai_role`
                 , `views`
                 , `created`
                 , `deleted_datetime`
                 , `deleted_user_id`
                 , `deleted_reason`
              FROM `user`
             WHERE `user_id` = ?
                 ;
        ';
        $parameters = [
            $userId,
        ];
        return $this->adapter->query($sql)->execute($parameters);
    }

    public function selectWhereUsername(string $username): Result
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
        return $this->adapter->query($sql)->execute($parameters);
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
