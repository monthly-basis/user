<?php
namespace LeoGalleguillos\User\Model\Service;

use LeoGalleguillos\User\Model\Entity as UserEntity;
use LeoGalleguillos\User\Model\Service as UserService;

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
