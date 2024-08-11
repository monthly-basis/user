<?php
namespace LeoGalleguillos\UserTest\Model\Service\Photo;

use LeoGalleguillos\User\Model\Entity as UserEntity;
use LeoGalleguillos\User\Model\Service as UserService;
use LeoGalleguillos\User\Model\Table as UserTable;
use PHPUnit\Framework\TestCase;

class IncrementViewsTest extends TestCase
{
    protected function setUp()
    {
        $this->photoTableMock = $this->createMock(
            UserTable\Photo::class
        );
        $this->incrementViewsService = new UserService\Photo\IncrementViews(
            $this->photoTableMock
        );
    }

    public function testInitialize()
    {
        $this->assertInstanceOf(
            UserService\Photo\IncrementViews::class,
            $this->incrementViewsService
        );
    }

    public function testIncrementViews()
    {
        $photoEntity = new UserEntity\Photo();
        $photoEntity->setPhotoId(123);

        $this->photoTableMock->method('updateViewsWherePhotoId')->willReturn(true);

        $this->assertTrue(
            $this->incrementViewsService->incrementViews($photoEntity)
        );
    }
}
