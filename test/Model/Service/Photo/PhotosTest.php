<?php
namespace LeoGalleguillos\UserTest\Model\Service\Photo;

use LeoGalleguillos\User\Model\Entity as UserEntity;
use LeoGalleguillos\User\Model\Service as UserService;
use LeoGalleguillos\User\Model\Table as UserTable;
use PHPUnit\Framework\TestCase;

class PhotosTest extends TestCase
{
    protected function setUp()
    {
        $this->photoTableMock = $this->createMock(
            UserTable\Photo::class
        );
        $this->photosService = new UserService\Photo\Photos(
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
}
