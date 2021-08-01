<?php
namespace MonthlyBasis\User\Model\Table;

use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\Driver\Pdo\Result;
use MonthlyBasis\User\Model\Db as UserDb;

class Register
{
    protected Adapter $adapter;
    protected UserDb\Sql $sql;

    public function __construct(UserDb\Sql $sql)
    {
        $this->sql     = $sql;
        $this->adapter = $sql->getAdapter();
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
        return $this->adapter
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
        $row = $this->adapter->query($sql)->execute()->current();
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
        return $this->adapter->query($sql)->execute($parameters);
    }
}
