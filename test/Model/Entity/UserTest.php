<?php
namespace MonthlyBasis\UserTest\Model\Entity;

use DateTime;
use MonthlyBasis\User\Model\Entity as UserEntity;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    protected UserEntity\User $userEntity;

    protected function setUp(): void
    {
        $this->userEntity = new UserEntity\User();
    }

    public function test_magicMethods()
    {
        $this->assertFalse(
            isset($this->userEntity->httpsToken)
        );
        $this->userEntity->httpsToken = 'abcdefg';
        $this->assertSame(
            'abcdefg',
            $this->userEntity->httpsToken
        );
        $this->assertTrue(
            isset($this->userEntity->httpsToken)
        );
    }

    public function test_properties()
    {
        $this->userEntity->emoji12Id = 1;
        $this->userEntity->image128Rru = '/path/to/128x128.jpeg';
        $this->userEntity->image256Rru = '/path/to/256x256.jpeg';
        $this->userEntity->image512Rru = '/path/to/512x512.jpeg';
        $this->userEntity->image1024Rru = '/path/to/1024x1024.jpeg';

        $this->assertSame(
            1,
            $this->userEntity->emoji12Id
        );
        $this->assertSame(
            '/path/to/128x128.jpeg',
            $this->userEntity->image128Rru
        );
        $this->assertSame(
            '/path/to/256x256.jpeg',
            $this->userEntity->image256Rru
        );
        $this->assertSame(
            '/path/to/512x512.jpeg',
            $this->userEntity->image512Rru
        );
        $this->assertSame(
            '/path/to/1024x1024.jpeg',
            $this->userEntity->image1024Rru
        );
    }

    public function test_settersAndGetters()
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

        $httpsToken = 'the-https-token';
        $this->userEntity->setHttpsToken($httpsToken);
        $this->assertSame(
            $httpsToken,
            $this->userEntity->getHttpsToken()
        );

        $loginToken = 'the-login-token';
        $this->assertSame(
            $this->userEntity,
            $this->userEntity->setLoginToken($loginToken)
        );
        $this->assertSame(
            $loginToken,
            $this->userEntity->getLoginToken()
        );

        $passwordHash = 'the password hash';
        $this->assertSame(
            $this->userEntity,
            $this->userEntity->setPasswordHash($passwordHash)
        );
        $this->assertSame(
            $passwordHash,
            $this->userEntity->getPasswordHash()
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
