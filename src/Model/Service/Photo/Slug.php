<?php
namespace LeoGalleguillos\User\Model\Service\Photo;

use LeoGalleguillos\String\Model\Service as StringService;
use LeoGalleguillos\User\Model\Entity as UserEntity;
use LeoGalleguillos\User\Model\Table as UserTable;

class Slug
{
    /**
     * Construct.
     *
     * @param UserTable\Photo $photoTable
     */
    public function __construct(
        StringService\UrlFriendly $urlFriendlyService
    ) {
        $this->urlFriendlyService = $urlFriendlyService;
    }

    /**
     * Get slug.
     *
     * @param UserEntity\Photo $photoEntity
     * @return string
     */
    public function getSlug(
        UserEntity\Photo $photoEntity
    ) : string {
        return $this->urlFriendlyService->getUrlFriendly(
            $photoEntity->getTitle()
        );
    }
}
