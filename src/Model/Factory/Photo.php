<?php
namespace LeoGalleguillos\User\Model\Factory;

use ArrayObject;
use DateTime;
use LeoGalleguillos\Image\Model\Entity as ImageEntity;
use LeoGalleguillos\User\Model\Entity as UserEntity;
use LeoGalleguillos\User\Model\Factory as UserFactory;

class Photo
{
    /**
     * Build from array object.
     *
     * @param ArrayObject $arrayObject
     * @return UserEntity\Photo
     */
    public function buildFromArrayObject(
        ArrayObject $arrayObject
    ) : UserEntity\Photo {
        $photo = new UserEntity\Photo();

        $original = new ImageEntity\Image();
        $original->setRootRelativeUrl(
            '/uploads/photos/'
            . $arrayObject['photo_id']
            . '/original.'
            . $arrayObject['extension']
        );
        $photo->setOriginal($original);

        return $photo;
    }
}
