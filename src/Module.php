<?php
namespace LeoGalleguillos\User;

use LeoGalleguillos\Flash\Model\Service as FlashService;
use LeoGalleguillos\ReCaptcha\Model\Service as ReCaptchaService;
use LeoGalleguillos\String\Model\Service as StringService;
use LeoGalleguillos\User\Controller as UserController;
use LeoGalleguillos\User\Model\Factory as UserFactory;
use LeoGalleguillos\User\Model\Service as UserService;
use LeoGalleguillos\User\Model\Table as UserTable;
use LeoGalleguillos\User\View\Helper as UserHelper;

class Module
{
    public function getConfig()
    {
        return [
            'view_helpers' => [
                'aliases' => [
                    'getLoggedInUser'          => UserHelper\LoggedInUser::class,
                    'isUserLoggedIn'           => UserHelper\LoggedIn::class,
                    'isLoginReCaptchaRequired' => UserHelper\Login\ReCaptchaRequired::class,
                    'getBirthdaySelectsHtml'   => UserHelper\BirthdaySelectsHtml::class,
                    'getDisplayNameOrUsername' => UserHelper\DisplayNameOrUsername::class,
                    'getUserRootRelativeUrl'   => UserHelper\RootRelativeUrl::class,
                    'getUserFactory'           => UserHelper\Factory\User::class,
                    'getUserHtml'              => UserHelper\UserHtml::class,
                ],
                'factories' => [
                    UserHelper\BirthdaySelectsHtml::class => function ($sm) {
                        return new UserHelper\BirthdaySelectsHtml();
                    },
                    UserHelper\DisplayNameOrUsername::class => function ($sm) {
                        return new UserHelper\DisplayNameOrUsername(
                            $sm->get(UserService\DisplayNameOrUsername::class)
                        );
                    },
                    UserHelper\LoggedIn::class => function ($serviceManager) {
                        return new UserHelper\LoggedIn(
                            $serviceManager->get(UserService\LoggedIn::class)
                        );
                    },
                    UserHelper\LoggedInUser::class => function ($serviceManager) {
                        return new UserHelper\LoggedInUser(
                            $serviceManager->get(UserService\LoggedInUser::class)
                        );
                    },
                    UserHelper\Login\ReCaptchaRequired::class => function ($serviceManager) {
                        return new UserHelper\Login\ReCaptchaRequired(
                            $serviceManager->get(UserService\Login\ReCaptchaRequired::class)
                        );
                    },
                    UserHelper\RootRelativeUrl::class => function ($serviceManager) {
                        return new UserHelper\RootRelativeUrl(
                            $serviceManager->get(UserService\RootRelativeUrl::class)
                        );
                    },
                    UserHelper\Factory\User::class => function ($serviceManager) {
                        return new UserHelper\Factory\User(
                            $serviceManager->get(UserFactory\User::class)
                        );
                    },
                    UserHelper\UserHtml::class => function ($serviceManager) {
                        return new UserHelper\UserHtml(
                            $serviceManager->get(StringService\Escape::class),
                            $serviceManager->get(UserService\RootRelativeUrl::class)
                        );
                    },
                ],
            ],
            'view_manager' => [
                'template_path_stack' => [
                    'leo-galleguillos/user' => '/../view',
                ],
            ],
        ];
    }

    public function getControllerConfig()
    {
        return [
            'factories' => [
                UserController\ResetPassword::class => function ($serviceManager) {
                    return new UserController\ResetPassword(
                        $serviceManager->get(FlashService\Flash::class),
                        $serviceManager->get(UserService\Password\Reset::class)
                    );
                },
                UserController\ResetPassword\Code::class => function ($serviceManager) {
                    return new UserController\ResetPassword\Code(
                        $serviceManager->get(FlashService\Flash::class),
                        $serviceManager->get(UserService\Logout::class),
                        $serviceManager->get(UserTable\ResetPassword::class),
                        $serviceManager->get(UserTable\ResetPasswordAccessLog::class),
                        $serviceManager->get(UserTable\User\PasswordHash::class)
                    );
                },
            ],
        ];
    }

    public function getServiceConfig()
    {
        return [
            'factories' => [
                UserFactory\Post::class => function ($serviceManager) {
                    return new UserFactory\Post();
                },
                UserFactory\User::class => function ($serviceManager) {
                    return new UserFactory\User(
                        $serviceManager->get(UserTable\User::class)
                    );
                },
                UserFactory\User\BuildFromCookies::class => function ($serviceManager) {
                    return new UserFactory\User\BuildFromCookies(
                        $serviceManager->get(UserService\LoggedInUser::class),
                        $serviceManager->get(UserTable\User::class),
                        $serviceManager->get(UserTable\User\LoginHash::class),
                        $serviceManager->get(UserTable\User\LoginIp::class)
                    );
                },
                UserService\Create::class => function ($sm) {
                    return new UserService\Create(
                        $sm->get('user'),
                        $sm->get(UserFactory\User::class),
                        $sm->get(UserTable\User::class),
                        $sm->get(UserTable\UserEmail::class)
                    );
                },
                UserService\DisplayNameOrUsername::class => function ($sm) {
                    return new UserService\DisplayNameOrUsername();
                },
                UserService\LoggedIn::class => function ($serviceManager) {
                    return new UserService\LoggedIn(
                        $serviceManager->get(UserTable\User::class)
                    );
                },
                UserService\LoggedInUser::class => function ($serviceManager) {
                    return new UserService\LoggedInUser(
                        $serviceManager->get(UserFactory\User::class),
                        $serviceManager->get(UserTable\User::class)
                    );
                },
                UserService\Login::class => function ($serviceManager) {
                    return new UserService\Login(
                        $serviceManager->get(FlashService\Flash::class),
                        $serviceManager->get(ReCaptchaService\Valid::class),
                        $serviceManager->get(UserFactory\User::class),
                        $serviceManager->get(UserService\Login\ReCaptchaRequired::class),
                        $serviceManager->get(UserTable\User::class),
                        $serviceManager->get(UserTable\User\LoginDateTime::class),
                        $serviceManager->get(UserTable\User\LoginHash::class),
                        $serviceManager->get(UserTable\User\LoginIp::class)
                    );
                },
                UserService\Login\ReCaptchaRequired::class => function ($serviceManager) {
                    return new UserService\Login\ReCaptchaRequired(
                        $serviceManager->get(UserTable\LoginLog::class)
                    );
                },
                UserService\Login\ShouldRedirectToReferer::class => function ($serviceManager) {
                    return new UserService\Login\ShouldRedirectToReferer(
                        $serviceManager->get(StringService\StartsWith::class)
                    );
                },
                UserService\Logout::class => function ($serviceManager) {
                    return new UserService\Logout();
                },
                UserService\Password\Reset::class => function ($serviceManager) {
                    $userConfig = $serviceManager->get('Config')['user'];
                    return new UserService\Password\Reset(
                        $serviceManager->get(FlashService\Flash::class),
                        $serviceManager->get(ReCaptchaService\Valid::class),
                        $userConfig['email-address'],
                        $userConfig['website-name'],
                        $serviceManager->get(UserFactory\User::class),
                        $serviceManager->get(UserService\Password\Reset\GenerateCode::class),
                        $serviceManager->get(UserService\Password\Reset\Url::class),
                        $serviceManager->get(UserTable\ResetPassword::class),
                        $serviceManager->get(UserTable\UserEmail::class)
                    );
                },
                UserService\Password\Reset\GenerateCode::class => function ($serviceManager) {
                    return new UserService\Password\Reset\GenerateCode();
                },
                UserService\Password\Reset\Url::class => function ($serviceManager) {
                    return new UserService\Password\Reset\Url();
                },
                UserService\Post::class => function ($serviceManager) {
                    return new UserService\Post(
                        $serviceManager->get(UserTable\Post::class)
                    );
                },
                UserService\Posts::class => function ($serviceManager) {
                    return new UserService\Posts(
                        $serviceManager->get(UserFactory\Post::class),
                        $serviceManager->get(UserTable\Post::class)
                    );
                },
                UserService\Register::class => function ($serviceManager) {
                    $config = $serviceManager->get('Config')['user']['register'];
                    return new UserService\Register(
                        $config,
                        $serviceManager->get(FlashService\Flash::class),
                        $serviceManager->get(ReCaptchaService\Valid::class),
                        $serviceManager->get(UserService\Register\FlashValues::class)
                    );
                },
                UserService\Register\Errors::class => function ($sm) {
                    $registerConfig = $sm->get('Config')['user']['register'];
                    return new UserService\Register\Errors(
                        $registerConfig,
                        $sm->get(ReCaptchaService\Valid::class),
                        $sm->get(UserTable\User\Username::class)
                    );
                },
                UserService\Register\FlashValues::class => function ($serviceManager) {
                    return new UserService\Register\FlashValues(
                        $serviceManager->get(FlashService\Flash::class)
                    );
                },
                UserService\RootRelativeUrl::class => function ($serviceManager) {
                    return new UserService\RootRelativeUrl();
                },
                UserService\Url::class => function ($sm) {
                    return new UserService\Url(
                        $sm->get(UserService\RootRelativeUrl::class)
                    );
                },
                UserService\User::class => function ($serviceManager) {
                    return new UserService\User(
                        $serviceManager->get(UserTable\User::class)
                    );
                },
                UserService\User\NewestUsers::class => function ($serviceManager) {
                    return new UserService\User\NewestUsers(
                        $serviceManager->get(UserFactory\User::class),
                        $serviceManager->get(UserTable\User::class)
                    );
                },
                UserService\Username\Change::class => function ($serviceManager) {
                    return new UserService\Username\Change(
                        $serviceManager->get(UserTable\User\Username::class)
                    );
                },
                UserTable\LoginLog::class => function ($serviceManager) {
                    return new UserTable\LoginLog(
                        $serviceManager->get('user')
                    );
                },
                UserTable\Post::class => function ($serviceManager) {
                    return new UserTable\Post(
                        $serviceManager->get('user')
                    );
                },
                UserTable\Register::class => function ($serviceManager) {
                    return new UserTable\Register(
                        $serviceManager->get('user')
                    );
                },
                UserTable\ResetPassword::class => function ($serviceManager) {
                    return new UserTable\ResetPassword(
                        $serviceManager->get('user')
                    );
                },
                UserTable\ResetPasswordAccessLog::class => function ($serviceManager) {
                    return new UserTable\ResetPasswordAccessLog(
                        $serviceManager->get('user')
                    );
                },
                UserTable\User::class => function ($serviceManager) {
                    return new UserTable\User(
                        $serviceManager->get('user')
                    );
                },
                UserTable\User\DisplayName::class => function ($serviceManager) {
                    return new UserTable\User\DisplayName(
                        $serviceManager->get('user')
                    );
                },
                UserTable\User\LoginDateTime::class => function ($serviceManager) {
                    return new UserTable\User\LoginDateTime(
                        $serviceManager->get('user')
                    );
                },
                UserTable\User\LoginHash::class => function ($serviceManager) {
                    return new UserTable\User\LoginHash(
                        $serviceManager->get('user')
                    );
                },
                UserTable\User\LoginIp::class => function ($serviceManager) {
                    return new UserTable\User\LoginIp(
                        $serviceManager->get('user')
                    );
                },
                UserTable\User\PasswordHash::class => function ($serviceManager) {
                    return new UserTable\User\PasswordHash(
                        $serviceManager->get('user')
                    );
                },
                UserTable\User\Username::class => function ($sm) {
                    return new UserTable\User\Username(
                        $sm->get('user')
                    );
                },
                UserTable\UserEmail::class => function ($serviceManager) {
                    return new UserTable\UserEmail(
                        $serviceManager->get('user')
                    );
                },
            ],
        ];
    }
}
