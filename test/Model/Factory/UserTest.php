<?php
namespace LeoGalleguillos\UserTest\Model\Factory;

use ArrayObject;
use MonthlyBasis\Flash\Model\Service as FlashService;
use LeoGalleguillos\User\Model\Entity as UserEntity;
use LeoGalleguillos\User\Model\Factory as UserFactory;
use LeoGalleguillos\User\Model\Service as UserService;
use LeoGalleguillos\User\Model\Table as UserTable;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    protected function setUp(): void
    {
        $this->userTableMock = $this->createMock(
            UserTable\User::class
        );
        $this->userFactory = new UserFactory\User(
            $this->userTableMock
        );
    }

    public function testBuildFromArray()
    {
        $array = [
            'display_name'    => 'Display Name',
            'user_id'         => 1,
            'username'        => 'Testing123',
            'welcome_message' => 'Welcome to my page.',
        ];
        $userEntity = new UserEntity\User();
        $userEntity->setDisplayName($array['display_name'])
                   ->setUserId($array['user_id'])
                   ->setUsername($array['username'])
                   ->setWelcomeMessage($array['welcome_message']);
        $this->assertEquals(
            $userEntity,
            $this->userFactory->buildFromArray($array)
        );

        $array = [
            'display_name'    => 'Hello World',
            'gender'          => 'M',
            'user_id'         => 5,
            'username'        => 'Testing123',
        ];
        $userEntity = new UserEntity\User();
        $userEntity->setDisplayName($array['display_name'])
                   ->setGender($array['gender'])
                   ->setUserId($array['user_id'])
                   ->setUsername($array['username']);
        $this->assertEquals(
            $userEntity,
            $this->userFactory->buildFromArray($array)
        );
    }

    public function testBuildFromUsername()
    {
        $array = [
            'user_id'         => 1,
            'username'        => 'Testing123',
            'welcome_message' => 'Welcome to my page.',
        ];

        $userEntity = new UserEntity\User();
        $userEntity->setUserId(1);
        $userEntity->username = 'Testing123';
        $userEntity->setViews(0);
        $userEntity->setWelcomeMessage('Welcome to my page.');

        $this->userTableMock->method('selectWhereUsername')->willReturn(
            $array
        );

        $this->assertEquals(
            $userEntity,
            $this->userFactory->buildFromUsername('Testing123')
        );
    }
}
