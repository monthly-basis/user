<?php
namespace MonthlyBasis\User;

use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use MonthlyBasis\Flash\Model\Service as FlashService;
use MonthlyBasis\ReCaptcha\Model\Service as ReCaptchaService;
use MonthlyBasis\SimpleEmailService\Model\Service as SimpleEmailServiceService;
use MonthlyBasis\StopForumSpam\Model\Service as StopForumSpamService;
use MonthlyBasis\String\Model\Service as StringService;
use MonthlyBasis\User\Controller as UserController;
use MonthlyBasis\User\Model\Db as UserDb;
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
                    'account' => [
                        'type'    => Literal::class,
                        'options' => [
                            'route'    => '/account',
                        ],
                        'may_terminate' => false,
                        'child_routes' => [
                            'change-password' => [
                                'type'    => Literal::class,
                                'options' => [
                                    'route'    => '/change-password',
                                    'defaults' => [
                                        'controller' => UserController\Account\ChangePassword::class,
                                        'action'     => 'change-password',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                        ],
                    ],
                    'activate' => [
                        'type'    => Segment::class,
                        'options' => [
                            'route'    => '/activate/:registerId/:activationCode',
                            'defaults' => [
                                'controller' => UserController\Activate::class,
                                'action'     => 'index',
                            ],
                        ],
                    ],
                    'login' => [
                        'type'    => Segment::class,
                        'options' => [
                            'route'    => '/login[/:action]',
                            'defaults' => [
                                'controller' => UserController\Login::class,
                                'action'     => 'index',
                            ],
                        ],
                    ],
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
                    'getBirthdaySelectsHtml'   => UserHelper\BirthdaySelectsHtml::class,
                    'getDisplayNameOrUsername' => UserHelper\DisplayNameOrUsername::class,
                    'getUserRootRelativeUrl'   => UserHelper\RootRelativeUrl::class,
                    'getUserFactory'           => UserHelper\Factory\User::class,
                    'getUserHtml'              => UserHelper\UserHtml::class,
                    /*
                     * @deprecated 'isUserLoggedIn' is a misnomer since a user cannot
                     *             exist without being logged in.
                     *             Use 'isVisitorLoggedIn' view helper instead.
                     *
                     */
                    'isUserLoggedIn'           => UserHelper\LoggedIn::class,
                    'isVisitorLoggedIn'        => UserHelper\LoggedIn::class,
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
                UserController\Account\ChangePassword::class => function ($sm) {
                    return new UserController\Account\ChangePassword();
                },
                UserController\Activate::class => function ($sm) {
                    return new UserController\Activate(
                        $sm->get(UserService\Activate::class)
                    );
                },
                UserController\Login::class => function ($sm) {
                    return new UserController\Login(
                        $sm->get(FlashService\Flash::class),
                        $sm->get(UserFactory\User::class),
                        $sm->get(UserService\LoggedIn::class),
                        $sm->get(UserService\LoggedInUser::class),
                        $sm->get(UserService\Login::class),
                        $sm->get(UserTable\LoginLog::class),
                    );
                },
                UserController\SignUp::class => function ($sm) {
                    return new UserController\SignUp(
                        $sm->get(FlashService\Flash::class),
                        $sm->get(UserService\LoggedInUser::class),
                        $sm->get(UserService\Register::class),
                        $sm->get(UserService\Url::class),
                    );
                },
                UserController\ResetPassword::class => function ($sm) {
                    return new UserController\ResetPassword(
                        $sm->get(FlashService\Flash::class),
                        $sm->get(UserService\LoggedInUser::class),
                        $sm->get(UserService\Password\Reset::class),
                        $sm->get(UserService\Url::class),
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
                UserDb\Sql::class => function ($sm) {
                    return new UserDb\Sql(
                        $sm->get('user')
                    );
                },
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
                UserService\Activate::class => function ($sm) {
                    return new UserService\Activate(
                        $sm->get('user')->getDriver()->getConnection(),
                        $sm->get(UserTable\ActivateLog::class),
                        $sm->get(UserTable\Register::class),
                        $sm->get(UserTable\User::class),
                        $sm->get(UserTable\UserEmail::class),
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
                UserService\Email\Exists::class => function ($sm) {
                    return new UserService\Email\Exists(
                        $sm->get(UserTable\UserEmail::class)
                    );
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
                        $sm->get(StringService\Random::class),
                        $sm->get(UserFactory\User::class),
                        $sm->get(UserService\Password\Valid::class),
                        $sm->get(UserTable\User::class),
                        $sm->get(UserTable\User\LoginDateTime::class),
                        $sm->get(UserTable\User\UserId::class),
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
                        $sm->get(StringService\Random::class),
                        $sm->get(UserFactory\User::class),
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
                UserService\Password\Valid::class => function ($sm) {
                    return new UserService\Password\Valid();
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
                        $sm->get(SimpleEmailServiceService\Send\Conditionally::class),
                        $sm->get(StringService\Random::class),
                        $sm->get(UserService\Register\Errors::class),
                        $sm->get(UserService\Register\FlashValues::class),
                        $sm->get(UserTable\Register::class),
                    );
                },
                UserService\Register\Errors::class => function ($sm) {
                    return new UserService\Register\Errors(
                        $sm->get(ReCaptchaService\Valid::class),
                        $sm->get(StopForumSpamService\IpAddress\Toxic::class),
                        $sm->get(UserService\Email\Exists::class),
                        $sm->get(UserService\Register\Errors\Birthday::class),
                        $sm->get(UserService\Username\Exists::class),
                    );
                },
                UserService\Register\Errors\Birthday::class => function ($sm) {
                    return new UserService\Register\Errors\Birthday(
                        $sm->get(UserTable\RegisterNotOldEnoughLog::class)
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
                UserService\Username\Exists::class => function ($sm) {
                    return new UserService\Username\Exists(
                        $sm->get(UserTable\User::class)
                    );
                },
                UserTable\ActivateLog::class => function ($sm) {
                    return new UserTable\ActivateLog(
                        $sm->get(UserDb\Sql::class)
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
                        $sm->get(UserDb\Sql::class)
                    );
                },
                UserTable\RegisterNotOldEnoughLog::class => function ($sm) {
                    return new UserTable\RegisterNotOldEnoughLog(
                        $sm->get(UserDb\Sql::class)
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
                UserTable\User\PasswordHash::class => function ($sm) {
                    return new UserTable\User\PasswordHash(
                        $sm->get('user')
                    );
                },
                UserTable\User\UserId::class => function ($sm) {
                    return new UserTable\User\UserId(
                        $sm->get(UserDb\Sql::class)
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
