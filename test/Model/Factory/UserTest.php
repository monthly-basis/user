<?php
namespace MonthlyBasis\UserTest\Model\Factory;

use Laminas\Db\Adapter\Driver\Pdo\Result;
use MonthlyBasis\Flash\Model\Service as FlashService;
use MonthlyBasis\LaminasTest\Hydrator as LaminasTestHydrator;
use MonthlyBasis\User\Model\Entity as UserEntity;
use MonthlyBasis\User\Model\Exception as UserException;
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

    public function test_buildFromArray()
    {
        $array = [
            'display_name'    => 'Display Name',
            'https_token'     => 'the-https-token',
            'password_hash'   => 'the password hash',
            'user_id'         => 1,
            'username'        => 'Testing123',
            'welcome_message' => 'Welcome to my page.',
        ];
        $userEntity = (new UserEntity\User())
            ->setDisplayName($array['display_name'])
            ->setHttpsToken($array['https_token'])
            ->setPasswordHash($array['password_hash'])
            ->setUserId($array['user_id'])
            ->setUsername($array['username'])
            ->setWelcomeMessage($array['welcome_message'])
            ;
        $this->assertEquals(
            $userEntity,
            $this->userFactory->buildFromArray($array)
        );

        $array = [
            'display_name'    => 'Hello World',
            'gender'          => 'M',
            'password_hash'   => 'the password hash',
            'user_id'         => 5,
            'username'        => 'Testing123',
        ];
        $userEntity = (new UserEntity\User())
            ->setDisplayName($array['display_name'])
            ->setGender($array['gender'])
            ->setPasswordHash($array['password_hash'])
            ->setUserId($array['user_id'])
            ->setUsername($array['username'])
        ;
        $this->assertEquals(
            $userEntity,
            $this->userFactory->buildFromArray($array)
        );
    }

    public function test_buildFromUserId_invalidUserId_throwsUserException()
    {
        $this->expectException(UserException::class);

        $countableIteratorHydrator = new LaminasTestHydrator\CountableIterator();
        $resultMock = $this->createMock(
            Result::class
        );
        $countableIteratorHydrator->hydrate(
            $resultMock,
            [],
        );

        $this->userTableMock->method('selectWhereUserId')->willReturn(
            $resultMock
        );

        $this->userFactory->buildFromUserId(12345);
    }

    public function test_buildFromUserId_validUserId_userObject()
    {
        $countableIteratorHydrator = new LaminasTestHydrator\CountableIterator();
        $resultMock = $this->createMock(
            Result::class
        );
        $countableIteratorHydrator->hydrate(
            $resultMock,
            [
                [
                    'password_hash'   => 'the password hash',
                    'user_id'         => '1',
                    'username'        => 'Testing123',
                    'welcome_message' => 'Welcome to my page.',
                ]
            ],
        );

        $userEntity = (new UserEntity\User())
            ->setPasswordHash('the password hash')
            ->setUserId(1)
            ->setUsername('Testing123')
            ->setViews(0)
            ->setWelcomeMessage('Welcome to my page.')
            ;

        $this->userTableMock->method('selectWhereUserId')->willReturn(
            $resultMock
        );

        $this->assertEquals(
            $userEntity,
            $this->userFactory->buildFromUserId(1)
        );
    }

    public function test_buildFromUsername_invalidUsername_throwsUserException()
    {
        $this->expectException(UserException::class);

        $countableIteratorHydrator = new LaminasTestHydrator\CountableIterator();
        $resultMock = $this->createMock(
            Result::class
        );
        $countableIteratorHydrator->hydrate(
            $resultMock,
            [],
        );

        $this->userTableMock->method('selectWhereUsername')->willReturn(
            $resultMock
        );

        $this->userFactory->buildFromUsername('InvalidUsername');
    }

    public function test_buildFromUsername_validUsername_userObject()
    {
        $countableIteratorHydrator = new LaminasTestHydrator\CountableIterator();
        $resultMock = $this->createMock(
            Result::class
        );
        $countableIteratorHydrator->hydrate(
            $resultMock,
            [
                [
                    'user_id'         => '1',
                    'username'        => 'Testing123',
                    'password_hash'   => 'the password hash',
                    'welcome_message' => 'Welcome to my page.',
                ]
            ],
        );

        $userEntity = (new UserEntity\User())
            ->setPasswordHash('the password hash')
            ->setUserId(1)
            ->setUsername('Testing123')
            ->setViews(0)
            ->setWelcomeMessage('Welcome to my page.')
            ;

        $this->userTableMock->method('selectWhereUsername')->willReturn(
            $resultMock
        );

        $this->assertEquals(
            $userEntity,
            $this->userFactory->buildFromUsername('Testing123')
        );
    }
}
