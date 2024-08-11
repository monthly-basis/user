<?php
namespace MonthlyBasis\User\Model\Service;

use MonthlyBasis\User\Model\Entity as UserEntity;
use MonthlyBasis\User\Model\Service as UserService;

class Url
{
    public function __construct(
        UserService\RootRelativeUrl $rootRelativeUrlService
    ) {
        $this->rootRelativeUrlService = $rootRelativeUrlService;
    }

    public function getUrl(UserEntity\User $userEntity): string
    {
        return 'https://'
             . $_SERVER['HTTP_HOST']
             . '/users/'
             . $userEntity->getUserId()
             . '/'
             . urlencode($userEntity->getUsername());
    }
}
