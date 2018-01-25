<?php
namespace LeoGalleguillos\User\Model\Table;

use Zend\Db\Adapter\Adapter;

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
        $passwordHash
    ) {
        $sql = '
            INSERT
              INTO `register` (`activation_code`, `username`, `email`, `password_hash`)
            VALUES (?, ?, ?, ?)
                 ;
        ';
        $parameters = [
            $activationCode,
            $username,
            $email,
            $passwordHash
        ];
        return $this->adapter
                    ->query($sql, $parameters)
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
              FROM `register`
             WHERE `register_id` = ?
               AND `activation_code` = ?
                 ;
        ';
        return $this->adapter->query($sql, [$registerId, $activationCode])->current();
    }
}
