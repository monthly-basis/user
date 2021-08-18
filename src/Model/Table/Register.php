<?php
namespace MonthlyBasis\User\Model\Table;

use Laminas\Db\Adapter\Driver\Pdo\Result;
use MonthlyBasis\User\Model\Db as UserDb;

class Register
{
    protected UserDb\Sql $sql;

    public function __construct(UserDb\Sql $sql)
    {
        $this->sql = $sql;
    }

    public function insert(
        string $activationCode,
        $username,
        $email,
        $passwordHash,
        string $birthday,
        string $gender = null
    ) {
        $sql = '
            INSERT
              INTO `register`
                   (
                         `activation_code`
                       , `username`
                       , `email`
                       , `password_hash`
                       , `birthday`
                       , `gender`
                   )
            VALUES (?, ?, ?, ?, ?, ?)
                 ;
        ';
        $parameters = [
            $activationCode,
            $username,
            $email,
            $passwordHash,
            $birthday,
            $gender
        ];
        return $this->sql
            ->getAdapter()
            ->query($sql)
            ->execute($parameters)
            ->getGeneratedValue();
    }

    public function selectCount()
    {
        $sql = '
            SELECT COUNT(*) AS `count`
              FROM `register`
                 ;
        ';
        $row = $this->sql->getAdapter()->query($sql)->execute()->current();
        return (int) $row['count'];
    }

    public function selectWhereRegisterIdAndActivationCode(
        int $registerId,
        string $activationCode
    ): Result {
        $sql = '
            SELECT `username`
                 , `email`
                 , `password_hash`
                 , `birthday`
                 , `gender`
              FROM `register`
             WHERE `register_id` = ?
               AND `activation_code` = ?
                 ;
        ';
        $parameters = [
            $registerId,
            $activationCode,
        ];
        return $this->sql->getAdapter()->query($sql)->execute($parameters);
    }

    public function updateSetActivatedWhereRegisterId(
        bool $activated,
        int $registerId
    ): Result {
        $update = $this->sql
            ->update('register')
            ->set([
               'activated' => intval($activated),
            ])
            ->where([
               'register_id' => $registerId,
            ])
            ;
        return $this->sql->prepareStatementForSqlObject($update)->execute();
    }
}
