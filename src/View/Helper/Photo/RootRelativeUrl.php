<?php
namespace LeoGalleguillos\User\View\Helper\Photo;

use LeoGalleguillos\User\Model\Entity as UserEntity;
use LeoGalleguillos\User\Model\Service as UserService;
use LeoGalleguillos\User\View\Helper as UserHelper;
use Zend\View\Helper\AbstractHelper;

class RootRelativeUrl extends AbstractHelper
{
    public function __construct(
        UserService\Photo\RootRelativeUrl $rootRelativeUrlService
    ) {
        $this->rootRelativeUrlService = $rootRelativeUrlService;
    }

    /**
     * Get root relative URL.
     *
     * @param UserEntity\Photo $photoEntity
     * @return string
     */
    public function __invoke(
        UserEntity\Photo $photoEntity
    ) : string {
        return $this->rootRelativeUrlService->getRootRelativeUrl($photoEntity);
    }
}
