<?php
namespace MonthlyBasis\UserTest\View\Helper;

use MonthlyBasis\String\Model\Service as StringService;
use MonthlyBasis\User\Model\Entity as UserEntity;
use MonthlyBasis\User\View\Helper as UserHelper;
use MonthlyBasis\User\Model\Service as UserService;
use PHPUnit\Framework\TestCase;

class UserHtmlTest extends TestCase
{
    protected function setUp(): void
    {
        $this->escapeService = new StringService\Escape();
        $this->rootRelativeUrlService = new UserService\RootRelativeUrl();
        $this->userHtmlHelper = new UserHelper\UserHtml(
            $this->escapeService,
            $this->rootRelativeUrlService
        );
    }

    public function testInvoke()
    {
        $userEntity = new UserEntity\User();
        $userEntity->setUserId(12345);
        $userEntity->setUsername('LeoGalleguillos');
        $html = '<a href="/users/12345/LeoGalleguillos">LeoGalleguillos</a>';
        $this->assertSame(
            $html,
            $this->userHtmlHelper->__invoke($userEntity)
        );

        $userEntity->setDisplayName('Leo & Galleguillos');
        $html = '<a href="/users/12345/LeoGalleguillos">Leo &amp; Galleguillos</a>';
        $this->assertSame(
            $html,
            $this->userHtmlHelper->__invoke($userEntity)
        );
    }
}
