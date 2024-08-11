<?php
namespace MonthlyBasis\User\Model\Table;

use Exception;
use Laminas\Db\Adapter\Adapter;

class UserEmail
{
    /**
     * @var Adapter
     */
    private $adapter;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    public function insert(
        $userId,
        $address
    ) {
        $sql = '
            INSERT
              INTO `user_email` (`user_id`, `address`)
            VALUES (?, ?)
                 ;
        ';
        $parameters = [
            $userId,
            $address
        ];
        return (bool) $this->adapter
                    ->query($sql, $parameters)
                    ->getAffectedRows();
    }

    public function isAddressInTable($address)
    {
        $sql = '
            SELECT COUNT(*) AS `count`
              FROM `user_email`
             WHERE `address` = ?
                 ;
        ';
        $row = $this->adapter->query($sql, [$address])->current();
        return (bool) $row['count'];
    }

    public function selectAddresses($userId)
    {
        $sql = '
            SELECT `user_email`.`address`
              FROM `user_email`
             WHERE `user_email`.`user_id` = ?
                 ;
        ';
        $results = $this->adapter->query($sql, [$userId]);

        $addresses = [];

        foreach ($results as $row) {
            $addresses[] = $row['address'];
        }

        return $addresses;
    }

    public function selectCount()
    {
        $sql = '
            SELECT COUNT(*) AS `count`
              FROM `user_email`
                 ;
        ';
        $row = $this->adapter->query($sql)->execute()->current();
        return (int) $row['count'];
    }

    public function selectUserIdWhereAddress(
        string $address
    ): int {
        $sql = '
            SELECT `user_email`.`user_id`
              FROM `user_email`
             WHERE `user_email`.`address` = ?
                 ;
        ';
        $parameters = [
            $address,
        ];
        $userId = $this->adapter->query($sql)->execute($parameters)->current()['user_id'];

        if (empty($userId)) {
            throw new Exception('User ID where address not found.');
        }

        return $userId;
    }
}
