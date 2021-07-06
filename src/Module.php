<?php
namespace MonthlyBasis\User;

use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use MonthlyBasis\Flash\Model\Service as FlashService;
use MonthlyBasis\ReCaptcha\Model\Service as ReCaptchaService;
use MonthlyBasis\SimpleEmailService\Model\Service as SimpleEmailServiceService;
use MonthlyBasis\String\Model\Service as StringService;
use MonthlyBasis\User\Controller as UserController;
use MonthlyBasis\User\Model\Factory as UserFactory;
use MonthlyBasis\User\Model\Service as UserService;
use MonthlyBasis\User\Model\Table as UserTable;
use MonthlyBasis\User\View\Helper as UserHelper;

class Module
{
    public function getConfig()
    {
        return [
            'router' => [
                'routes' => [
                    'sign-up' => [
                        'type'    => Segment::class,
                        'options' => [
                            'route'    => '/sign-up[/:action]',
                            'defaults' => [
                                'controller' => UserController\SignUp::class,
                                'action'     => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'reset-password' => [
                        'type'    => Literal::class,
                        'options' => [
                            'route'    => '/reset-password',
                            'defaults' => [
                                'controller' => UserController\ResetPassword::class,
                                'action'     => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'user-id' => [
                                'type'    => Segment::class,
                                'options' => [
                                    'route'    => '/:userId',
                                    'constraints' => [
                                        'userId' => '\d+',
                                    ],
                                ],
                                'may_terminate' => false,
                                'child_routes' => [
                                    'code' => [
                                        'type'    => Segment::class,
                                        'options' => [
                                            'route'    => '/:code',
                                            'defaults' => [
                                                'controller' => UserController\ResetPassword\UserId\Code::class,
                                                'action'     => 'index',
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            'email-sent' => [
                                'type'    => Literal::class,
                                'options' => [
                                    'route'    => '/email-sent',
                                    'defaults' => [
                                        'controller' => UserController\ResetPassword::class,
                                        'action'     => 'email-sent',
                                    ],
                                ],
                            ],
                            'success' => [
                                'type'    => Literal::class,
                                'options' => [
                                    'route'    => '/success',
                                    'defaults' => [
                                        'controller' => UserController\ResetPassword::class,
                                        'action'     => 'success',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
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
                    UserHelper\LoggedIn::class => function ($sm) {
                        return new UserHelper\LoggedIn(
                            $sm->get(UserService\LoggedIn::class)
                        );
                    },
                    UserHelper\LoggedInUser::class => function ($sm) {
                        return new UserHelper\LoggedInUser(
                            $sm->get(UserService\LoggedInUser::class)
                        );
                    },
                    UserHelper\Login\ReCaptchaRequired::class => function ($sm) {
                        return new UserHelper\Login\ReCaptchaRequired(
                            $sm->get(UserService\Login\ReCaptchaRequired::class)
                        );
                    },
                    UserHelper\RootRelativeUrl::class => function ($sm) {
                        return new UserHelper\RootRelativeUrl(
                            $sm->get(UserService\RootRelativeUrl::class)
                        );
                    },
                    UserHelper\Factory\User::class => function ($sm) {
                        return new UserHelper\Factory\User(
                            $sm->get(UserFactory\User::class)
                        );
                    },
                    UserHelper\UserHtml::class => function ($sm) {
                        return new UserHelper\UserHtml(
                            $sm->get(StringService\Escape::class),
                            $sm->get(UserService\RootRelativeUrl::class)
                        );
                    },
                ],
            ],
            'view_manager' => [
                'template_path_stack' => [
                    'monthly-basis/user' => __DIR__ . '/../view',
                ],
            ],
        ];
    }

    public function getControllerConfig()
    {
        return [
            'factories' => [
                UserController\SignUp::class => function ($sm) {
                    return new UserController\SignUp(
                        $sm->get(FlashService\Flash::class),
                        $sm->get(UserService\Register::class),
                    );
                },
                UserController\ResetPassword::class => function ($sm) {
                    return new UserController\ResetPassword(
                        $sm->get(FlashService\Flash::class),
                        $sm->get(UserService\Password\Reset::class)
                    );
                },
                UserController\ResetPassword\UserId\Code::class => function ($sm) {
                    return new UserController\ResetPassword\UserId\Code(
                        $sm->get(FlashService\Flash::class),
                        $sm->get(UserFactory\Password\Reset\FromUserIdAndCode::class),
                        $sm->get(UserService\Logout::class),
                        $sm->get(UserService\Password\Reset\Accessed\ConditionallyUpdate::class),
                        $sm->get(UserService\Password\Reset\Expired::class),
                        $sm->get(UserTable\ResetPassword::class),
                        $sm->get(UserTable\ResetPasswordAccessLog::class),
                        $sm->get(UserTable\User\PasswordHash::class),
                    );
                },
            ],
        ];
    }

    public function getServiceConfig()
    {
        return [
            'factories' => [
                UserFactory\Post::class => function ($sm) {
                    return new UserFactory\Post();
                },
                UserFactory\Password\Reset\FromArray::class => function ($sm) {
                    return new UserFactory\Password\Reset\FromArray();
                },
                UserFactory\Password\Reset\FromUserIdAndCode::class => function ($sm) {
                    return new UserFactory\Password\Reset\FromUserIdAndCode(
                        $sm->get(UserFactory\Password\Reset\FromArray::class),
                        $sm->get(UserTable\ResetPassword::class),
                    );
                },
                UserFactory\User::class => function ($sm) {
                    return new UserFactory\User(
                        $sm->get(UserTable\User::class)
                    );
                },
                UserFactory\User\BuildFromCookies::class => function ($sm) {
                    return new UserFactory\User\BuildFromCookies(
                        $sm->get(UserService\LoggedInUser::class),
                        $sm->get(UserTable\User::class),
                        $sm->get(UserTable\User\LoginHash::class),
                        $sm->get(UserTable\User\LoginIp::class)
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
                UserService\LoggedIn::class => function ($sm) {
                    return new UserService\LoggedIn(
                        $sm->get(UserTable\User::class)
                    );
                },
                UserService\LoggedInUser::class => function ($sm) {
                    return new UserService\LoggedInUser(
                        $sm->get(UserFactory\User::class),
                        $sm->get(UserTable\User::class)
                    );
                },
                UserService\Login::class => function ($sm) {
                    return new UserService\Login(
                        $sm->get(FlashService\Flash::class),
                        $sm->get(ReCaptchaService\Valid::class),
                        $sm->get(UserFactory\User::class),
                        $sm->get(UserService\Login\ReCaptchaRequired::class),
                        $sm->get(UserTable\User::class),
                        $sm->get(UserTable\User\LoginDateTime::class),
                        $sm->get(UserTable\User\LoginHash::class),
                        $sm->get(UserTable\User\LoginIp::class)
                    );
                },
                UserService\Login\ReCaptchaRequired::class => function ($sm) {
                    return new UserService\Login\ReCaptchaRequired(
                        $sm->get(UserTable\LoginLog::class)
                    );
                },
                UserService\Login\ShouldRedirectToReferer::class => function ($sm) {
                    return new UserService\Login\ShouldRedirectToReferer(
                        $sm->get(StringService\StartsWith::class)
                    );
                },
                UserService\Logout::class => function ($sm) {
                    return new UserService\Logout();
                },
                UserService\Password\Reset::class => function ($sm) {
                    $userConfig = $sm->get('Config')['user'];
                    return new UserService\Password\Reset(
                        $sm->get(FlashService\Flash::class),
                        $sm->get(ReCaptchaService\Valid::class),
                        $sm->get(SimpleEmailServiceService\Send\Conditionally::class),
                        $userConfig['email-address'],
                        $userConfig['website-name'],
                        $sm->get(UserFactory\User::class),
                        $sm->get(UserService\Password\Reset\GenerateCode::class),
                        $sm->get(UserService\Password\Reset\Url::class),
                        $sm->get(UserTable\ResetPassword::class),
                        $sm->get(UserTable\UserEmail::class)
                    );
                },
                UserService\Password\Reset\Accessed\ConditionallyUpdate::class => function ($sm) {
                    return new UserService\Password\Reset\Accessed\ConditionallyUpdate(
                        $sm->get(UserTable\ResetPassword::class),
                    );
                },
                UserService\Password\Reset\Expired::class => function ($sm) {
                    return new UserService\Password\Reset\Expired();
                },
                UserService\Password\Reset\GenerateCode::class => function ($sm) {
                    return new UserService\Password\Reset\GenerateCode();
                },
                UserService\Password\Reset\Url::class => function ($sm) {
                    return new UserService\Password\Reset\Url();
                },
                UserService\Post::class => function ($sm) {
                    return new UserService\Post(
                        $sm->get(UserTable\Post::class)
                    );
                },
                UserService\Posts::class => function ($sm) {
                    return new UserService\Posts(
                        $sm->get(UserFactory\Post::class),
                        $sm->get(UserTable\Post::class)
                    );
                },
                UserService\Register::class => function ($sm) {
                    $config = $sm->get('Config')['user'];
                    return new UserService\Register(
                        $config,
                        $sm->get(FlashService\Flash::class),
                        $sm->get(ReCaptchaService\Valid::class),
                        $sm->get(SimpleEmailServiceService\Send\Conditionally::class),
                        $sm->get(UserService\Register\FlashValues::class),
                        $sm->get(UserTable\Register::class),
                    );
                },
                UserService\Register\Errors::class => function ($sm) {
                    $registerConfig = $sm->get('Config')['user'];
                    return new UserService\Register\Errors(
                        $registerConfig,
                        $sm->get(ReCaptchaService\Valid::class),
                        $sm->get(UserTable\User\Username::class)
                    );
                },
                UserService\Register\FlashValues::class => function ($sm) {
                    return new UserService\Register\FlashValues(
                        $sm->get(FlashService\Flash::class)
                    );
                },
                UserService\RootRelativeUrl::class => function ($sm) {
                    return new UserService\RootRelativeUrl();
                },
                UserService\Url::class => function ($sm) {
                    return new UserService\Url(
                        $sm->get(UserService\RootRelativeUrl::class)
                    );
                },
                UserService\User::class => function ($sm) {
                    return new UserService\User(
                        $sm->get(UserTable\User::class)
                    );
                },
                UserService\User\NewestUsers::class => function ($sm) {
                    return new UserService\User\NewestUsers(
                        $sm->get(UserFactory\User::class),
                        $sm->get(UserTable\User::class)
                    );
                },
                UserService\Username\Change::class => function ($sm) {
                    return new UserService\Username\Change(
                        $sm->get(UserTable\User\Username::class)
                    );
                },
                UserTable\LoginLog::class => function ($sm) {
                    return new UserTable\LoginLog(
                        $sm->get('user')
                    );
                },
                UserTable\Post::class => function ($sm) {
                    return new UserTable\Post(
                        $sm->get('user')
                    );
                },
                UserTable\Register::class => function ($sm) {
                    return new UserTable\Register(
                        $sm->get('user')
                    );
                },
                UserTable\ResetPassword::class => function ($sm) {
                    return new UserTable\ResetPassword(
                        $sm->get('user')
                    );
                },
                UserTable\ResetPasswordAccessLog::class => function ($sm) {
                    return new UserTable\ResetPasswordAccessLog(
                        $sm->get('user')
                    );
                },
                UserTable\User::class => function ($sm) {
                    return new UserTable\User(
                        $sm->get('user')
                    );
                },
                UserTable\User\DisplayName::class => function ($sm) {
                    return new UserTable\User\DisplayName(
                        $sm->get('user')
                    );
                },
                UserTable\User\LoginDateTime::class => function ($sm) {
                    return new UserTable\User\LoginDateTime(
                        $sm->get('user')
                    );
                },
                UserTable\User\LoginHash::class => function ($sm) {
                    return new UserTable\User\LoginHash(
                        $sm->get('user')
                    );
                },
                UserTable\User\LoginIp::class => function ($sm) {
                    return new UserTable\User\LoginIp(
                        $sm->get('user')
                    );
                },
                UserTable\User\PasswordHash::class => function ($sm) {
                    return new UserTable\User\PasswordHash(
                        $sm->get('user')
                    );
                },
                UserTable\User\Username::class => function ($sm) {
                    return new UserTable\User\Username(
                        $sm->get('user')
                    );
                },
                UserTable\UserEmail::class => function ($sm) {
                    return new UserTable\UserEmail(
                        $sm->get('user')
                    );
                },
            ],
        ];
    }

    public function onBootstrap()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }
}
