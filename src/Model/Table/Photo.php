<?php
namespace LeoGalleguillos\User\Model\Table;

use ArrayObject;
use Generator;
use Zend\Db\Adapter\Adapter;

class Photo
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
     * @return int
     */
    public function insert(
        $userId,
        $extension,
        $title,
        $description
    ) {
        $sql = '
            INSERT
              INTO `photo` (`user_id`, `extension`, `title`, `description`, `views`, `created`)
            VALUES (?, ?, ?, ?, 0, UTC_TIMESTAMP())
                 ;
        ';
        $parameters = [
            $userId,
            $extension,
            $title,
            $description,
        ];
        return (int) $this->adapter
                          ->query($sql, $parameters)
                          ->getGeneratedValue();
    }

    public function selectCount()
    {
        $sql = '
            SELECT COUNT(*) AS `count`
              FROM `photo`
                 ;
        ';
        $row = $this->adapter->query($sql)->execute()->current();

        return (int) $row['count'];
    }

    public function selectCountWhereUserId(int $userId)
    {
        $sql = '
            SELECT COUNT(*) AS `count`
              FROM `photo`
             WHERE `photo`.`user_id` = :userId
                 ;
        ';
        $parameters = [
            'userId' => $userId,
        ];
        $row = $this->adapter->query($sql)->execute($parameters)->current();

        return (int) $row['count'];
    }

    /**
     * @return Generator
     */
    public function selectOrderByCreatedDesc()
    {
        $sql = '
            SELECT `photo`.`photo_id`
                 , `photo`.`user_id`
                 , `photo`.`extension`
                 , `photo`.`title`
                 , `photo`.`description`
                 , `photo`.`views`
                 , `photo`.`created`
              FROM `photo`
             ORDER
                BY `photo`.`created` DESC
             LIMIT 10
                 ;
        ';
        $resultSet = $this->adapter->query($sql)->execute();

        foreach ($resultSet as $arrayObject) {
            yield $arrayObject;
        }
    }

    public function selectWherePhotoId(int $photoId) : array
    {
        $sql = '
            SELECT `photo_id`
                 , `user_id`
                 , `extension`
                 , `title`
                 , `description`
                 , `views`
                 , `created`
              FROM `photo`
             WHERE `photo_id` = ?
                 ;
        ';
        return $this->adapter->query($sql)->execute([$photoId])->current();
    }

    public function selectWhereUserId(int $userId) : Generator
    {
        $sql = '
            SELECT `photo`.`photo_id`
                 , `photo`.`user_id`
                 , `photo`.`extension`
                 , `photo`.`title`
                 , `photo`.`description`
                 , `photo`.`views`
                 , `photo`.`created`
              FROM `photo`
             WHERE `photo`.`user_id` = :userId
             ORDER
                BY `photo`.`created` DESC
             LIMIT 10
                 ;
        ';
        $parameters = [
            'userId' => $userId,
        ];
        $resultSet = $this->adapter->query($sql)->execute($parameters);

        foreach ($resultSet as $array) {
            yield $array;
        }
    }

    public function updateViewsWherePhotoId(int $photoId) : bool
    {
        $sql = '
            UPDATE `photo`
               SET `photo`.`views` = `photo`.`views` + 1
             WHERE `photo`.`photo_id` = ?
                 ;
        ';
        $parameters = [
            $photoId
        ];
        return (bool) $this->adapter->query($sql, $parameters)->getAffectedRows();
    }
}
