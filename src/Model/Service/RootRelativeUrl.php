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
        $rootRelativeUrl = '/users';

        $includeUserId = $this->configEntity['root-relative-url']['include-user-id'] ?? true;
        if ($includeUserId) {
            $rootRelativeUrl .= '/' . $userEntity->userId;
        }

        $rootRelativeUrl .= '/' . urlencode($userEntity->getUsername());

        return $rootRelativeUrl;
    }
}
