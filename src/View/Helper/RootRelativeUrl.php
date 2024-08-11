<?php
namespace LeoGalleguillos\User\View\Helper;

use LeoGalleguillos\User\Model\Entity as UserEntity;
use LeoGalleguillos\User\Model\Service as UserService;
use Laminas\View\Helper\AbstractHelper;

class RootRelativeUrl extends AbstractHelper
{
    public function __construct(
        UserService\RootRelativeUrl $rootRelativeUrlService
    ) {
        $this->rootRelativeUrlService = $rootRelativeUrlService;
    }

    public function __invoke(UserEntity\User $userEntity)
    {
        return $this->rootRelativeUrlService->getRootRelativeUrl($userEntity);
    }
}
