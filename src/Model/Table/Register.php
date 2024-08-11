<?php
namespace MonthlyBasis\User\Model\Table;

use Laminas\Db\Adapter\Adapter;

class Register
{
    /**
     * @var Adapter
     */
    protected $adapter;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    public function insert(
        $activationCode,
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
        int $activationCode
    ) {
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
        return $this->adapter->query($sql, [$registerId, $activationCode])->current();
    }
}
