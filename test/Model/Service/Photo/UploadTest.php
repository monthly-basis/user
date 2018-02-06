<?php
namespace LeoGalleguillos\UserTest\Model\Service\Photo;

use LeoGalleguillos\User\Model\Entity as UserEntity;
use LeoGalleguillos\User\Model\Service as UserService;
use LeoGalleguillos\User\Model\Table as UserTable;
use PHPUnit\Framework\TestCase;

class UploadTest extends TestCase
{
    protected function setUp()
    {
        $this->photoTableMock = $this->createMock(
            UserTable\Photo::class
        );
        $this->uploadService = new UserService\Photo\Upload(
            $this->photoTableMock
        );
    }

    public function testInitialize()
    {
        $this->assertInstanceOf(
            UserService\Photo\Upload::class,
            $this->uploadService
        );
    }
}
