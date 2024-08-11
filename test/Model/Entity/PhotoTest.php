<?php
namespace LeoGalleguillos\UserTest\Model\Entity;

use DateTime;
use LeoGalleguillos\User\Model\Entity as UserEntity;
use PHPUnit\Framework\TestCase;

class PhotoTest extends TestCase
{
    protected function setUp()
    {
        $this->photoEntity = new UserEntity\Photo();
    }

    public function testInitialize()
    {
        $this->assertInstanceOf(
            UserEntity\Photo::class,
            $this->photoEntity
        );
    }

    public function testGettersAndSetters()
    {
        $created = new DateTime();
        $this->assertSame(
            $this->photoEntity,
            $this->photoEntity->setCreated($created)
        );
        $this->assertSame(
            $created,
            $this->photoEntity->getCreated()
        );
    }
}
