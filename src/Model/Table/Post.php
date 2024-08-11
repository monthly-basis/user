<?php
namespace MonthlyBasis\User\Model\Table;

use ArrayObject;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\Exception\InvalidQueryException;

/**
 * @deprecated Use MonthlyBasis\Post module instead
 */
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
     * @throws InvalidQueryException If foreign key contraint fails
     * @return int
     */
    public function insert(
        int $fromUserId,
        int $toUserId,
        string $message
    ) {
        $sql = '
            INSERT
              INTO `post` (`from_user_id`, `to_user_id`, `message`, `created`)
            VALUES (?, ?, ?, NOW())
                 ;
        ';
        $parameters = [
            $fromUserId,
            $toUserId,
            $message,
        ];
        return (int) $this->adapter
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
            SELECT `post`.`post_id`

                 , `from_user`.`user_id` AS `from_user_user_id`
                 , `from_user`.`username` AS `from_user_username`

                 , `to_user`.`user_id` AS `to_user_user_id`
                 , `to_user`.`username` AS `to_user_username`

                 , `post`.`message`
                 , `post`.`created`

              FROM `post`

              JOIN `user` AS `from_user`
                ON `from_user`.`user_id` = `post`.`from_user_id`

              JOIN `user` AS `to_user`
                ON `to_user`.`user_id` = `post`.`to_user_id`

             WHERE `to_user_id` = ?

             ORDER
                BY `created` DESC,
                   `post_id` DESC
                 ;
        ';
        $resultSet = $this->adapter->query($sql, [$toUserId]);

        $arrayObjects = new ArrayObject();
        foreach ($resultSet as $arrayObject) {
            $arrayObjects[] = $arrayObject;
        }
        return $arrayObjects;
    }
}
