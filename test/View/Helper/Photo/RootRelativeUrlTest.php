<?php
namespace LeoGalleguillos\UserTest\View\Helper;

use LeoGalleguillos\User\Model\Entity as UserEntity;
use LeoGalleguillos\User\Model\Service as UserService;
use LeoGalleguillos\User\View\Helper as UserHelper;
use PHPUnit\Framework\TestCase;

class RootRelativeUrlTest extends TestCase
{
    protected function setUp()
    {
        $this->rootRelativeUrlServiceMock = $this->createMock(
            UserService\Photo\RootRelativeUrl::class
        );
        $this->rootRelativeUrlHelper = new UserHelper\Photo\RootRelativeUrl(
            $this->rootRelativeUrlServiceMock
        );
    }

    public function testInitialize()
    {
        $this->assertInstanceOf(
            UserHelper\Photo\RootRelativeUrl::class,
            $this->rootRelativeUrlHelper
        );
    }

    public function testInvoke()
    {
        $photoEntity = new UserEntity\Photo();

        $this->rootRelativeUrlServiceMock->method('getRootRelativeUrl')->willReturn(
            '/photos/12345/The-title'
        );

        $this->assertSame(
            '/photos/12345/The-title',
            $this->rootRelativeUrlHelper->__invoke($photoEntity)
        );
    }
}
