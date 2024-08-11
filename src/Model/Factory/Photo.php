<?php
namespace LeoGalleguillos\User\Model\Factory;

use DateTime;
use LeoGalleguillos\Image\Model\Entity as ImageEntity;
use LeoGalleguillos\Image\Model\Service as ImageService;
use LeoGalleguillos\User\Model\Entity as UserEntity;
use LeoGalleguillos\User\Model\Table as UserTable;

class Photo
{
    /**
     * Construct.
     *
     * @param UserTable\Photo $photoTable
     */
    public function __construct(
        ImageService\Thumbnail\Create $createThumbnailService,
        UserTable\Photo $photoTable
    ) {
        $this->createThumbnailService = $createThumbnailService;
        $this->photoTable             = $photoTable;
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
              ->setUserId($array['user_id'])
              ->setDescription($array['description'])
              ->setExtension($array['extension'])
              ->setCreated(new DateTime($array['created']))
              ->setViews((int) $array['views']);

        $original = new ImageEntity\Image();
        $original->setRootRelativeUrl(
            '/uploads/photos/'
            . $array['photo_id']
            . '/original.'
            . $array['extension']
        );
        $photo->setOriginal($original);

        $thumbnails = [];
        $thumbnail300RootPath = $_SERVER['DOCUMENT_ROOT'] . '/uploads/photos/' . $photo->getPhotoId() . '/300.' . $photo->getExtension();

        if (!file_exists($thumbnail300RootPath)) {
            $thumbnail = $this->createThumbnailService->create(
                $original,
                300,
                $thumbnail300RootPath
            );
            $thumbnails['300'] = $thumbnail;
        }
        $photo->setThumbnails($thumbnails);

        return $photo;
    }

    /**
     * Build from photo ID.
     *
     * @param int $photoId
     * @return UserEntity\Photo
     */
    public function buildFromPhotoId(
        int $photoId
    ) : UserEntity\Photo {
        $array = $this->photoTable->selectWherePhotoId($photoId);
        return $this->buildFromArray($array);
    }
}
