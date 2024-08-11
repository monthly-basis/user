<?php
namespace LeoGalleguillos\User\Model\Factory;

use DateTime;
use LeoGalleguillos\Image\Model\Entity as ImageEntity;
use LeoGalleguillos\User\Model\Entity as UserEntity;
use LeoGalleguillos\User\Model\Factory as UserFactory;
use LeoGalleguillos\User\Model\Table as UserTable;

class Photo
{
    public function __construct(
        UserTable\Photo $photoTable
    ) {
        $this->photoTable = $photoTable;
    }

    /**
     * Build from array object.
     *
     * @param array $array
     * @return UserEntity\Photo
     */
    public function buildFromArray(
        array $array
    ) : UserEntity\Photo {
        $photo = new UserEntity\Photo();

        $photo->setPhotoId($array['photo_id'])
              ->setTitle($array['title'])
              ->setDescription($array['description'])
              ->setCreated(new DateTime($array['created']));

        $original = new ImageEntity\Image();
        $original->setRootRelativeUrl(
            '/uploads/photos/'
            . $array['photo_id']
            . '/original.'
            . $array['extension']
        );
        $photo->setOriginal($original);

        return $photo;
    }
}
