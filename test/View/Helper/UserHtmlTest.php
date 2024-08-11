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
        $this->rootRelativeUrlServiceMock = $this->createMock(
            UserService\RootRelativeUrl::class
        );
        $this->userHtmlHelper = new UserHelper\UserHtml(
            $this->escapeService,
            $this->rootRelativeUrlServiceMock
        );
    }

    public function test___invoke_noDisplayName_expectedString()
    {
        $userEntity = new UserEntity\User();
        $userEntity->setUserId(12345);
        $userEntity->setUsername('LeoGalleguillos');

        $this->rootRelativeUrlServiceMock
             ->expects($this->once())
             ->method('getRootRelativeUrl')
             ->with($userEntity)
             ->willReturn('/users/12345/LeoGalleguillos')
             ;

        $html = '<a href="/users/12345/LeoGalleguillos">LeoGalleguillos</a>';
        $this->assertSame(
            $html,
            $this->userHtmlHelper->__invoke($userEntity)
        );
    }

    public function test___invoke_displayName_expectedString()
    {
        $userEntity = new UserEntity\User();
        $userEntity->setUserId(12345);
        $userEntity->setUsername('LeoGalleguillos');
        $userEntity->setDisplayName('Leo & Galleguillos');

        $this->rootRelativeUrlServiceMock
             ->expects($this->once())
             ->method('getRootRelativeUrl')
             ->with($userEntity)
             ->willReturn('/users/12345/LeoGalleguillos')
             ;

        $html = '<a href="/users/12345/LeoGalleguillos">Leo &amp; Galleguillos</a>';
        $this->assertSame(
            $html,
            $this->userHtmlHelper->__invoke($userEntity)
        );
    }
}
