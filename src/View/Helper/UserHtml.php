<?php
namespace LeoGalleguillos\User\View\Helper;

use MonthlyBasis\String\Model\Service as StringService;
use LeoGalleguillos\User\Model\Entity as UserEntity;
use LeoGalleguillos\User\Model\Service as UserService;
use TypeError;
use Zend\View\Helper\AbstractHelper;

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
