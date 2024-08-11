<?php
namespace LeoGalleguillos\User\Model\Table;

use ArrayObject;
use Exception;
use Generator;
use Zend\Db\Adapter\Adapter;

class LoginLog
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
        string $ip,
        int $success
    ) : int {
        $sql = '
            INSERT
              INTO `login_log` (`ip`, `success`, `created`)
            VALUES (?, ?, UTC_TIMESTAMP())
                 ;
        ';
        $parameters = [
            $ip,
            $success
        ];
        return (int) $this->adapter
                          ->query($sql, $parameters)
                          ->getGeneratedValue();
    }

    public function selectCount()
    {
        $sql = '
            SELECT COUNT(*) AS `count`
              FROM `login_log`
                 ;
        ';
        $row = $this->adapter->query($sql)->execute()->current();
        return (int) $row['count'];
    }
}
