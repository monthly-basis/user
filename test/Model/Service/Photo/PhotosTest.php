<?php
namespace LeoGalleguillos\UserTest\Model\Service\Photo;

use LeoGalleguillos\User\Model\Entity as UserEntity;
use LeoGalleguillos\User\Model\Factory as UserFactory;
use LeoGalleguillos\User\Model\Service as UserService;
use LeoGalleguillos\User\Model\Table as UserTable;
use PHPUnit\Framework\TestCase;

class PhotosTest extends TestCase
{
    protected function setUp()
    {
        $this->photoFactoryMock = $this->createMock(
            UserFactory\Photo::class
        );
        $this->photoTableMock = $this->createMock(
            UserTable\Photo::class
        );
        $this->photosService = new UserService\Photo\Photos(
            $this->photoFactoryMock,
            $this->photoTableMock
        );
    }

    public function testInitialize()
    {
        $this->assertInstanceOf(
            UserService\Photo\Photos::class,
            $this->photosService
        );
    }

    public function testGetNewestPhotos()
    {
        $array = [
            $array = [
                'photo_id'    => '2',
                'extension'   => 'jpg',
                'title'       => 'title',
                'description' => 'description',
                'views'       => '0',
                'created'     => '0000-00-00 00:00:00',
            ]
        ];
        $this->photoTableMock->method('selectOrderByCreatedDesc')->willReturn(
            $array
        );
        $this->assertInstanceOf(
            UserEntity\Photo::class,
            $this->photosService->getNewestPhotos()->current()
        );
    }
}
