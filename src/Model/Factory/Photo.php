<?php
namespace LeoGalleguillos\User\Model\Factory;

use DateTime;
use LeoGalleguillos\Image\Model\Entity as ImageEntity;
use LeoGalleguillos\User\Model\Entity as UserEntity;
use LeoGalleguillos\User\Model\Factory as UserFactory;

class Photo
{
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

        $photo->setTitle($array['title'])
              ->setDescription($array['description']);

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
