<?php
namespace LeoGalleguillos\User\Model\Service\Photo;

use LeoGalleguillos\User\Model\Entity as UserEntity;
use LeoGalleguillos\User\Model\Table as UserTable;

class IncrementViews
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
     * Increment views.
     *
     * @param UserEntity\Photo $photoEntity
     * @return bool
     */
    public function incrementViews(UserEntity\Photo $photoEntity)
    {
        return $this->photoTable->updateViewsWherePhotoId($photoEntity->getPhotoId());
    }
}
