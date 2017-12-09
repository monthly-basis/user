<?php
namespace LeoGalleguillos\User\Model\Table;

use Zend\Db\Adapter\Adapter;

class User
{
    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @return int Primary key
     */
    public function insert(
        string $username,
        string $passwordHash,
        string $fullName
    ) {
        $sql = '
            INSERT
              INTO `user` (`username`, `password_hash`, `full_name`)
            VALUES (?, ?, ?)
                 ;
        ';
        $parameters = [
            $username,
            $passwordHash,
            $fullName
        ];
        return $this->adapter
                    ->query($sql, $parameters)
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
}
