<?php
namespace MonthlyBasis\User\View\Helper;

use MonthlyBasis\User\Model\Entity as UserEntity;
use MonthlyBasis\User\Model\Service as UserService;
use Laminas\View\Helper\AbstractHelper;

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
