<?php
namespace LeoGalleguillos\User\View\Helper;

use LeoGalleguillos\User\Model\Service as UserService;
use Zend\View\Helper\AbstractHelper;

class DisplayNameOrUsername extends AbstractHelper
{
    public function __construct(
        UserService\DisplayNameOrUsername $displayNameOrUsernameService
    ) {
        $this->displayNameOrUsernameService = $displayNameOrUsernameService;
    }

    public function __invoke(UserEntity\User $userEntity): string
    {
        return $this->displayNameOrUsernameService->getDisplayNameOrUsername(
            $userEntity
        );
    }
}
