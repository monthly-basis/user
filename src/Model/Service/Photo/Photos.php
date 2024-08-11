<?php
namespace LeoGalleguillos\User\Model\Service\Photo;

use ArrayObject;
use LeoGalleguillos\User\Model\Entity as UserEntity;
use LeoGalleguillos\User\Model\Table as UserTable;

class Photos
{
    /**
     * Construct.
     *
     * @param UserTable\Photo $photoTable
     */
    public function __construct(
        UserTable\Photo $photoTable
    ) {
        $this->photoTable = $photoTable;
    }

    /**
     * Get newest photos.
     *
     * @return ArrayObject
     */
    public function getNewestPhotos()
    {
        $photos = new ArrayObject();
    }
}
