<?php
namespace MonthlyBasis\User\Model\Service;

use MonthlyBasis\User\Model\Entity as UserEntity;
use MonthlyBasis\User\Model\Service as UserService;

class Url
{
    public function __construct(
        protected UserService\RootRelativeUrl $rootRelativeUrlService
    ) {
    }

    public function getUrl(UserEntity\User $userEntity): string
    {
        return 'https://'
             . $_SERVER['HTTP_HOST']
             . $this->rootRelativeUrlService->getRootRelativeUrl($userEntity);
    }
}
