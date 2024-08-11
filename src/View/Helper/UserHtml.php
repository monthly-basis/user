<?php
namespace MonthlyBasis\User\View\Helper;

use MonthlyBasis\String\Model\Service as StringService;
use MonthlyBasis\User\Model\Entity as UserEntity;
use MonthlyBasis\User\Model\Service as UserService;
use TypeError;
use Laminas\View\Helper\AbstractHelper;

class UserHtml extends AbstractHelper
{
    public function __construct(
        StringService\Escape $escapeService,
        UserService\RootRelativeUrl $rootRelativeUrlService
    ) {
        $this->escapeService          = $escapeService;
        $this->rootRelativeUrlService = $rootRelativeUrlService;
    }

    public function __invoke(UserEntity\User $userEntity)
    {
        try {
            $innerHtml = $userEntity->getDisplayName();
        } catch (TypeError $typeError) {
            $innerHtml = $userEntity->getUsername();
        }

        $rru = $this->rootRelativeUrlService->getRootRelativeUrl($userEntity);

        return "<a href=\"$rru\">"
            . $this->escapeService->escape($innerHtml)
            . '</a>';
    }
}
