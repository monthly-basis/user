<?php
namespace LeoGalleguillos\User\Model\Service\Photo;

use ArrayObject;
use Generator;
use LeoGalleguillos\Image\Model\Entity as ImageEntity;
use LeoGalleguillos\Photo\Model\Entity as PhotoEntity;
use LeoGalleguillos\User\Model\Entity as UserEntity;
use LeoGalleguillos\User\Model\Factory as UserFactory;
use LeoGalleguillos\User\Model\Table as UserTable;

class Photos
{
    /**
     * Construct.
     *
     * @param UserFactory\Photo $photoFactory
     * @param UserTable\Photo $photoTable
     */
    public function __construct(
        UserFactory\Photo $photoFactory,
        UserTable\Photo $photoTable
    ) {
        $this->photoFactory = $photoFactory;
        $this->photoTable   = $photoTable;
    }

    /**
     * Get newest photos.
     *
     * @return Generator
     */
    public function getNewestPhotos() : Generator
    {
        foreach ($this->photoTable->selectOrderByCreatedDesc() as $array) {
            yield $this->photoFactory->buildFromArray($array);
        }
    }

    /**
     * @return int
     */
    public function getNumberOfPhotosForUser(UserEntity\User $userEntity)
    {
        return $this->photoTable->selectCountWhereUserId($userEntity->getUserId());
    }

    public function getPhotosForUser(UserEntity\User $userEntity)
    {
        foreach ($this->photoTable->selectWhereUserId($userEntity->getUserId()) as $array) {
            yield $this->photoFactory->buildFromArray($array);
        }
    }
}
