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
    protected UserFactory\User $userFactory;
    protected UserTable\User $userTableMock;

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
            'emoji_12_id'     => 123,
            'https_token'     => 'the-https-token',
            'login_token'     => 'the-login-token',
            'open_ai_role'    => 'Open AI role',
            'image_128_rru'   => '/path/to/128x128.jpeg',
            'image_256_rru'   => '/path/to/256x256.jpeg',
            'image_512_rru'   => '/path/to/512x512.jpeg',
            'image_1024_rru'  => '/path/to/1024x1024.jpeg',
            'password_hash'   => 'the password hash',
            'user_id'         => 1,
            'username'        => 'Testing123',
            'welcome_message' => 'Welcome to my page.',
        ];
        $userEntity = (new UserEntity\User())
            ->setDisplayName($array['display_name'])
            ->setHttpsToken($array['https_token'])
            ->setLoginToken($array['login_token'])
            ->setPasswordHash($array['password_hash'])
            ->setUserId($array['user_id'])
            ->setUsername($array['username'])
            ->setWelcomeMessage($array['welcome_message'])
            ;
        $userEntity->emoji12Id = 123;
        $userEntity->openAiRole = 'Open AI role';
        $userEntity->image128Rru = '/path/to/128x128.jpeg';
        $userEntity->image256Rru = '/path/to/256x256.jpeg';
        $userEntity->image512Rru = '/path/to/512x512.jpeg';
        $userEntity->image1024Rru = '/path/to/1024x1024.jpeg';
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

    public function test_buildFromArray_missingOptionalFields_userEntity()
    {
        $array = [
            'user_id' => 1,
        ];
        $userEntity = (new UserEntity\User())
            ->setUserId($array['user_id'])
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
