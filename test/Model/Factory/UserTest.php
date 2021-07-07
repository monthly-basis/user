<?php
namespace MonthlyBasis\UserTest\Model\Factory;

use Laminas\Db\Adapter\Driver\Pdo\Result;
use MonthlyBasis\Flash\Model\Service as FlashService;
use MonthlyBasis\LaminasTest\Hydrator as LaminasTestHydrator;
use MonthlyBasis\User\Model\Entity as UserEntity;
use MonthlyBasis\User\Model\Factory as UserFactory;
use MonthlyBasis\User\Model\Service as UserService;
use MonthlyBasis\User\Model\Table as UserTable;
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

    public function test_buildFromUsername()
    {
        $countableIteratorHydrator = new LaminasTestHydrator\CountableIterator();
        $resultMock = $this->createMock(
            Result::class
        );
        $countableIteratorHydrator->hydrate(
            $resultMock,
            [
                [
                    'user_id'         => 1,
                    'username'        => 'Testing123',
                    'welcome_message' => 'Welcome to my page.',
                ]
            ],
        );

        $userEntity = new UserEntity\User();
        $userEntity->setUserId(1);
        $userEntity->username = 'Testing123';
        $userEntity->setViews(0);
        $userEntity->setWelcomeMessage('Welcome to my page.');

        $this->userTableMock->method('selectWhereUsername')->willReturn(
            $resultMock
        );

        $this->assertEquals(
            $userEntity,
            $this->userFactory->buildFromUsername('Testing123')
        );
    }
}
