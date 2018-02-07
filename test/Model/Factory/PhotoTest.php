<?php
namespace LeoGalleguillos\UserTest\Model\Factory;

use ArrayObject;
use LeoGalleguillos\Flash\Model\Service as FlashService;
use LeoGalleguillos\Image\Model\Entity as ImageEntity;
use LeoGalleguillos\User\Model\Entity as UserEntity;
use LeoGalleguillos\User\Model\Factory as UserFactory;
use LeoGalleguillos\User\Model\Service as UserService;
use LeoGalleguillos\User\Model\Table as UserTable;
use PHPUnit\Framework\TestCase;

class PhotoTest extends TestCase
{
    protected function setUp()
    {
        $this->photoFactory = new UserFactory\Photo();
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
        $arrayObject = new ArrayObject([
            'photo_id'    => '2',
            'extension'   => 'jpg',
            'title'       => 'title',
            'description' => 'description',
            'views'       => '0',
            'created'     => '0000-00-00 00:00:00',
        ]);
        $photoEntity = new UserEntity\Photo();

        $photoEntity->setTitle($arrayObject['title'])
                    ->setDescription($arrayObject['description']);

        $original = new ImageEntity\Image();
        $original->setRootRelativeUrl('/uploads/photos/2/original.jpg');

        $photoEntity->setOriginal($original);

        $this->assertEquals(
            $photoEntity,
            $this->photoFactory->buildFromArrayObject($arrayObject)
        );
    }
}
