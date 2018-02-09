<?php
namespace LeoGalleguillos\UserTest\Model\Service\Photo;

use LeoGalleguillos\String\Model\Service as StringService;
use LeoGalleguillos\User\Model\Entity as UserEntity;
use LeoGalleguillos\User\Model\Service as UserService;
use LeoGalleguillos\User\Model\Table as UserTable;
use PHPUnit\Framework\TestCase;

class SlugTest extends TestCase
{
    protected function setUp()
    {
        $this->urlFriendlyServiceMock = $this->createMock(
            StringService\UrlFriendly::class
        );
        $this->slugService = new UserService\Photo\Slug(
            $this->urlFriendlyServiceMock
        );
    }

    public function testInitialize()
    {
        $this->assertInstanceOf(
            UserService\Photo\Slug::class,
            $this->slugService
        );
    }

    public function testGetSlug()
    {
        $photoEntity = new UserEntity\Photo();
        $photoEntity->setTitle('The Title');
        $this->urlFriendlyServiceMock->method('getUrlFriendly')->willReturn(
            'the-slug'
        );
        $this->assertSame(
            'the-slug',
            $this->slugService->getSlug($photoEntity)
        );
    }
}
