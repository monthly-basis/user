<?php
namespace LeoGalleguillos\UserTest\Model\Service\Photo;

use LeoGalleguillos\String\Model\Service as StringService;
use LeoGalleguillos\User\Model\Entity as UserEntity;
use LeoGalleguillos\User\Model\Service as UserService;
use LeoGalleguillos\User\Model\Table as UserTable;
use PHPUnit\Framework\TestCase;

class RootRelativeUrlTest extends TestCase
{
    protected function setUp()
    {
        $this->slugServiceMock = $this->createMock(
            UserService\Photo\Slug::class
        );
        $this->rootRelativeUrlService = new UserService\Photo\RootRelativeUrl(
            $this->slugServiceMock
        );
    }

    public function testInitialize()
    {
        $this->assertInstanceOf(
            UserService\Photo\RootRelativeUrl::class,
            $this->rootRelativeUrlService
        );
    }

    public function testGetRootRelativeUrl()
    {
        $photoEntity = new UserEntity\Photo();
        $photoEntity->setPhotoId(12345)
                    ->setTitle('The Title');

        $this->slugServiceMock->method('getSlug')->willReturn('the-title');

        $this->assertSame(
            '/photos/12345/the-title',
            $this->rootRelativeUrlService->getRootRelativeUrl($photoEntity)
        );
    }
}
