<?php
namespace LeoGalleguillos\User\View\Helper;

use LeoGalleguillos\User\Model\Service as UserService;
use Zend\View\Helper\AbstractHelper;

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
