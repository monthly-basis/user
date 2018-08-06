<?php
namespace LeoGalleguillos\User;

use LeoGalleguillos\Flash\Model\Service as FlashService;
use LeoGalleguillos\ReCaptcha\Model\Service as ReCaptchaService;
use LeoGalleguillos\String\Model\Service as StringService;
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
                ],
                'factories' => [
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
                ],
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
                    return new UserService\Register(
                        $serviceManager->get(FlashService\Flash::class),
                        $serviceManager->get(ReCaptchaService\Valid::class),
                        $serviceManager->get(UserService\Register\FlashValues::class)
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
                UserTable\UserEmail::class => function ($serviceManager) {
                    return new UserTable\UserEmail(
                        $serviceManager->get('user')
                    );
                },
            ],
        ];
    }
}
