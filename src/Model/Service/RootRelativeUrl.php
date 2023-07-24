<?php
namespace MonthlyBasis\User\Model\Service;

use MonthlyBasis\User\Model\Entity as UserEntity;

class RootRelativeUrl
{
    public function __construct(
        protected UserEntity\Config $configEntity,
    ) {

    }
    public function getRootRelativeUrl(UserEntity\User $userEntity): string
    {
        return '/users/'
             . $userEntity->getUserId()
             . '/'
             . urlencode($userEntity->getUsername());
    }
}
