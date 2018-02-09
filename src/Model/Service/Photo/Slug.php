<?php
namespace LeoGalleguillos\User\Model\Service\Photo;

use LeoGalleguillos\String\Model\Service as StringService;
use LeoGalleguillos\User\Model\Entity as UserEntity;

class Slug
{
    /**
     * Construct.
     *
     * @param StringService\UrlFriendly $urlFriendlyService
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
