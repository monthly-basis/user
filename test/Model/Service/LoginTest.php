<?php
namespace MonthlyBasis\UserTest\Model\Service;

use Laminas\Db\Adapter\Driver\Pdo\Result;
use MonthlyBasis\LaminasTest\Hydrator as LaminasTestHydrator;
use MonthlyBasis\Flash\Model\Service as FlashService;
use MonthlyBasis\ReCaptcha\Model\Service as ReCaptchaService;
use MonthlyBasis\String\Model\Service as StringService;
use MonthlyBasis\User\Model\Entity as UserEntity;
use MonthlyBasis\User\Model\Factory as UserFactory;
use MonthlyBasis\User\Model\Service as UserService;
use MonthlyBasis\User\Model\Table as UserTable;
use PHPUnit\Framework\TestCase;

class LoginTest extends TestCase
{
    /**
     * @runInSeparateProcess Login service sends headers and therefore needs
     *                       to run in its own process.
     */
    protected function setUp(): void
    {
        $_SERVER['HTTP_HOST']   = 'example.com';
        $_SERVER['REMOTE_ADDR'] = '123.123.123.123';

        $this->flashServiceMock = $this->createMock(
            FlashService\Flash::class
        );
        $this->validReCaptchaServiceMock = $this->createMock(
            ReCaptchaService\Valid::class
        );
        $this->randomServiceMock = $this->createMock(
            StringService\Random::class
        );
        $this->userFactoryMock = $this->createMock(
            UserFactory\User::class
        );
        $this->validServiceMock = $this->createMock(
            UserService\Password\Valid::class
        );
        $this->userTableMock = $this->createMock(
            UserTable\User::class
        );
        $this->loginDateTimeTableMock = $this->createMock(
            UserTable\User\LoginDateTime::class
        );
        $this->userIdTableMock = $this->createMock(
            UserTable\User\UserId::class
        );
        $this->userTokenTableMock = $this->createMock(
            UserTable\UserToken::class
        );

        $this->loginService = new UserService\Login(
            $this->flashServiceMock,
            $this->validReCaptchaServiceMock,
            $this->randomServiceMock,
            $this->userFactoryMock,
            $this->validServiceMock,
            $this->userTableMock,
            $this->loginDateTimeTableMock,
            $this->userIdTableMock,
            $this->userTokenTableMock,
        );

        $this->countableIteratorHydrator = new LaminasTestHydrator\CountableIterator();
    }

    /**
     * @runInSeparateProcess
     */
    public function test_login_emptyPostData_false()
    {
        unset($_POST['username']);
        unset($_POST['password']);

        $this->assertFalse(
            $this->loginService->login()
        );
    }

    /**
     * @runInSeparateProcess
     */
    public function test_login_invalidReCaptcha_false()
    {
        $_POST['username'] = 'username';
        $_POST['password'] = 'password';

        $this->validReCaptchaServiceMock
             ->expects($this->exactly(1))
             ->method('isValid')
             ->willReturn(false)
             ;

        $this->assertFalse(
            $this->loginService->login()
        );
    }

    /**
     * @runInSeparateProcess
     */
    public function test_login_usernameWhichDoesNotExist_false()
    {
        $_POST['username'] = 'username-which-does-not-exist';
        $_POST['password'] = 'password';

        $this->validReCaptchaServiceMock
             ->expects($this->once())
             ->method('isValid')
             ->willReturn(true)
             ;

        $emptyResultMock = $this->createMock(
            Result::class
        );
        $this->countableIteratorHydrator->hydrate(
            $emptyResultMock,
            [],
        );
        $this->userTableMock
             ->expects($this->once())
             ->method('selectWhereUsername')
             ->willReturn($emptyResultMock)
             ;

        $this->assertFalse(
            $this->loginService->login()
        );
    }

    /**
     * @runInSeparateProcess
     */
    public function test_login_incorrectPassword_false()
    {
        $_POST['username'] = 'username';
        $_POST['password'] = 'incorrect password';

        $this->validReCaptchaServiceMock
             ->expects($this->once())
             ->method('isValid')
             ->willReturn(true)
             ;
        $wrongPasswordResultMock = $this->createMock(
            Result::class
        );
        $this->countableIteratorHydrator->hydrate(
            $wrongPasswordResultMock,
            [
                [
                    'user_id'       => '2718',
                    'username'      => 'username',
                    'password_hash' => 'password-hash-which-does-not-match-incorrect-password',
                ],
            ],
        );
        $this->userTableMock
             ->expects($this->once())
             ->method('selectWhereUsername')
             ->willReturn($wrongPasswordResultMock)
             ;
        $this->validServiceMock
             ->expects($this->once())
             ->method('isValid')
             ->with(
                 'incorrect password',
                 'password-hash-which-does-not-match-incorrect-password'
             )
             ->willReturn(false)
             ;

        $this->assertFalse(
            $this->loginService->login()
        );
    }

    /**
     * @runInSeparateProcess
     */
    public function test_login_validReCaptchaCorrectUsernameAndCorrectPassword_true()
    {
        $_POST['username'] = 'username';
        $_POST['password'] = 'correct password';

        $this->validReCaptchaServiceMock
             ->expects($this->once())
             ->method('isValid')
             ->willReturn(true)
             ;
        $correctUsernameAndPasswordResultMock = $this->createMock(
            Result::class
        );
        $this->countableIteratorHydrator->hydrate(
            $correctUsernameAndPasswordResultMock,
            [
                [
                    'user_id'       => '2718',
                    'username'      => 'username',
                    'password_hash' => '$2y$10$/O2EsOXRypBlEEuEVNHBa.Zd2p6jM3K3IkG3HzfaulFxArpbZC2y2',
                ],
            ],
        );
        $this->userTableMock
             ->expects($this->once())
             ->method('selectWhereUsername')
             ->willReturn($correctUsernameAndPasswordResultMock)
             ;
        $this->validServiceMock
             ->expects($this->once())
             ->method('isValid')
             ->with(
                 'correct password',
                 '$2y$10$/O2EsOXRypBlEEuEVNHBa.Zd2p6jM3K3IkG3HzfaulFxArpbZC2y2'
             )
             ->willReturn(true)
             ;
        $userEntity = (new UserEntity\User())
            ->setUserId(123)
            ->setUsername('username')
            ;
        $this->userFactoryMock
             ->expects($this->once())
             ->method('buildFromUserId')
             ->with(2718)
             ->willReturn($userEntity)
             ;
        $this->randomServiceMock
             ->expects($this->exactly(2))
             ->method('getRandomString')
             ->with(64)
             ->will(
                 $this->onConsecutiveCalls(
                     '5a153d2efedba593a3979bb7abaeb24443f1c33201de1a01da851fc982a6ba84',
                     'i8x33h0tbe0x7scgmh17vqlij0xwgpjbelq7g15a7u5jyxlxo2de7guflhxdvfl4',
                 )
             )
             ;
        $this->userIdTableMock
             ->expects($this->once())
             ->method('updateSetLoginHashHttpsTokenWhereUserId')
             ->with(
                 '5a153d2efedba593a3979bb7abaeb24443f1c33201de1a01da851fc982a6ba84',
                 'i8x33h0tbe0x7scgmh17vqlij0xwgpjbelq7g15a7u5jyxlxo2de7guflhxdvfl4',
                 123
             )
             ;
        $this->userIdTableMock
             ->expects($this->once())
             ->method('updateSetLoginIpWhereUserId')
             ->with('123.123.123.123', 123)
             ;
        $this->userTokenTableMock
             ->expects($this->once())
             ->method('insert')
             ->with(
                '2718',
                '5a153d2efedba593a3979bb7abaeb24443f1c33201de1a01da851fc982a6ba84',
                'i8x33h0tbe0x7scgmh17vqlij0xwgpjbelq7g15a7u5jyxlxo2de7guflhxdvfl4',
                // DateTime 30 days into the future,
             )
             ;

        $this->assertTrue(
            $this->loginService->login()
        );
    }
}
