<?php
namespace LeoGalleguillos\User\Model\Service\Photo;

use LeoGalleguillos\User\Model\Entity as UserEntity;
use LeoGalleguillos\User\Model\Service as UserService;

class RootRelativeUrl
{
    /**
     * Construct.
     *
     * @param UserTable\Photo $photoTable
     */
    public function __construct(
        UserService\Photo\Slug $slugService
    ) {
        $this->slugService = $slugService;
    }

    /**
     * Get root relative URL.
     *
     * @param UserEntity\Photo $photoEntity
     * @return string
     */
    public function getRootRelativeUrl(
        UserEntity\Photo $photoEntity
    ) : string {
        return '/photos/'
             . $photoEntity->getPhotoId()
             . '/'
             . $this->slugService->getSlug($photoEntity);
    }
}
