<?php
namespace LeoGalleguillos\User\Model\Table;

use ArrayObject;
use Zend\Db\Adapter\Adapter;

class Post
{
    /**
     * @var Adapter
     */
    private $adapter;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * Insert.
     *
     * @return int
     */
    public function insert(
        int $fromUserId,
        int $toUserId,
        string $message
    ) {
        $sql = '
            INSERT
              INTO `post` (`from_user_id`, `to_user_id`, `message`)
            VALUES (?, ?, ?)
                 ;
        ';
        $parameters = [
            $fromUserId,
            $toUserId,
            $message,
        ];
        return $this->adapter
                    ->query($sql, $parameters)
                    ->getGeneratedValue();
    }

    public function selectCount()
    {
        $sql = '
            SELECT COUNT(*) AS `count`
              FROM `post`
                 ;
        ';
        $row = $this->adapter->query($sql)->execute()->current();
        return (int) $row['count'];
    }

    /**
     * @return ArrayObject|bool
     *
     * @TODO Throw exception if no posts found.
     */
    public function selectWhereToUserId($toUserId)
    {
        $sql = '
            SELECT `post_id`
                 , `from_user_id`
                 , `to_user_id`
                 , `message`
              FROM `post`
             WHERE `to_user_id` = ?
                 ;
        ';
        $row = $this->adapter->query($sql, [$toUserId])->current();

        return $row;
    }
}
