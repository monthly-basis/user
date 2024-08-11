<?php
namespace LeoGalleguillos\UserTest\Model\Entity;

use DateTime;
use LeoGalleguillos\User\Model\Entity as UserEntity;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    protected function setUp(): void
    {
        $this->userEntity = new UserEntity\User();
    }

    public function testAttributes()
    {
        $this->assertObjectHasAttribute('userId', $this->userEntity);
        $this->assertObjectHasAttribute('username', $this->userEntity);
        $this->assertObjectHasAttribute('firstName', $this->userEntity);
        $this->assertObjectHasAttribute('lastName', $this->userEntity);
    }

    public function testGettersAndSetters()
    {
        $userId = 123;
        $this->userEntity->setUserId($userId);
        $this->assertSame(
            $userId,
            $this->userEntity->getUserId()
        );

        $created = new DateTime();
        $this->userEntity->setCreated($created);
        $this->assertSame(
            $created,
            $this->userEntity->getCreated()
        );

        $deletedDatetime = new DateTime();
        $this->userEntity->setDeletedDatetime($deletedDatetime);
        $this->assertSame(
            $deletedDatetime,
            $this->userEntity->getDeletedDateTime()
        );

        $deletedReason = 'multiple copyright strikes';
        $this->userEntity->setDeletedReason($deletedReason);
        $this->assertSame(
            $deletedReason,
            $this->userEntity->getDeletedReason()
        );

        $deletedUserId = 1;
        $this->userEntity->setDeletedUserId($deletedUserId);
        $this->assertSame(
            $deletedUserId,
            $this->userEntity->getDeletedUserId()
        );

        $gender = 'F';
        $this->userEntity->setGender($gender);
        $this->assertSame(
            $gender,
            $this->userEntity->getGender()
        );

        $username = 'myusername';
        $this->userEntity->setUsername($username);
        $this->assertSame(
            $username,
            $this->userEntity->getUsername()
        );

        $views = 123;
        $this->userEntity->setViews($views);
        $this->assertSame(
            $views,
            $this->userEntity->getViews()
        );

        $welcomeMessage = 'Welcome to my page.';
        $this->userEntity->setWelcomeMessage($welcomeMessage);
        $this->assertSame(
            $welcomeMessage,
            $this->userEntity->getWelcomeMessage()
        );
    }
}
