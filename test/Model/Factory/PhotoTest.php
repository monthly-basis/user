<?php
namespace LeoGalleguillos\UserTest\Model\Factory;

use ArrayObject;
use DateTime;
use LeoGalleguillos\Flash\Model\Service as FlashService;
use LeoGalleguillos\Image\Model\Entity as ImageEntity;
use LeoGalleguillos\Image\Model\Service as ImageService;
use LeoGalleguillos\User\Model\Entity as UserEntity;
use LeoGalleguillos\User\Model\Factory as UserFactory;
use LeoGalleguillos\User\Model\Service as UserService;
use LeoGalleguillos\User\Model\Table as UserTable;
use PHPUnit\Framework\TestCase;

class PhotoTest extends TestCase
{
    protected function setUp()
    {
        $this->createThumbnailServiceMock = $this->createMock(
            ImageService\Thumbnail\Create::class
        );
        $this->photoTableMock = $this->createMock(
            UserTable\Photo::class
        );
        $this->photoFactory = new UserFactory\Photo(
            $this->createThumbnailServiceMock,
            $this->photoTableMock
        );
    }

    public function testInitialize()
    {
        $this->assertInstanceOf(
            UserFactory\Photo::class,
            $this->photoFactory
        );
    }

    public function testBuildFromArrayObject()
    {
        $_SERVER['DOCUMENT_ROOT'] = $_SERVER['PWD'];
        $array = [
            'photo_id'    => '2',
            'extension'   => 'jpg',
            'title'       => 'title',
            'description' => 'description',
            'views'       => '5',
            'created'     => '0000-00-00 00:00:00',
            'user_id'     => '123',
        ];
        $original = new ImageEntity\Image();
        $original->setRootRelativeUrl('/uploads/photos/2/original.jpg');
        $original->setRootUrl($_SERVER['DOCUMENT_ROOT'] . '/uploads/photos/2/original.jpg');

        $photoEntity = new UserEntity\Photo();
        $photoEntity->setPhotoId(2)
                    ->setTitle('title')
                    ->setUserId(123)
                    ->setDescription('description')
                    ->setExtension('jpg')
                    ->setCreated(new DateTime('0000-00-00 00:00:00'))
                    ->setOriginal($original)
                    ->setViews(5);

        $imageEntity = new ImageEntity\Image();
        $this->createThumbnailServiceMock->method('create')->willReturn($imageEntity);
        $thumbnails = [
            '300' => $imageEntity,
        ];
        $photoEntity->setThumbnails($thumbnails);

        $this->assertEquals(
            $photoEntity,
            $this->photoFactory->buildFromArray($array)
        );
    }

    public function testBuildFromPhotoId()
    {
        $array = [
            'photo_id'    => '2',
            'extension'   => 'jpg',
            'title'       => 'title',
            'description' => 'description',
            'views'       => '0',
            'created'     => '0000-00-00 00:00:00',
            'user_id'     => '123',
        ];
        $this->photoTableMock->method('selectWherePhotoId')->willReturn(
            $array
        );

        $this->assertInstanceOf(
            UserEntity\Photo::class,
            $this->photoFactory->buildFromPhotoId(1)
        );
    }
}
