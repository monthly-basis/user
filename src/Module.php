<?php
namespace LeoGalleguillos\User;

use LeoGalleguillos\Flash\Model\Service as FlashService;
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
                    'loggedIn' => UserHelper\LoggedIn::class,
                ],
                'factories' => [
                    UserHelper\LoggedIn::class => function ($serviceManager) {
                        return new UserHelper\LoggedIn(
                            $serviceManager->get(UserService\LoggedIn::class)
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
                UserService\Register::class => function ($serviceManager) {
                    return new UserService\Register(
                        $serviceManager->get(FlashService\Flash::class)
                    );
                },
                UserService\LoggedIn::class => function ($serviceManager) {
                    return new UserService\LoggedIn();
                },
                UserService\LoggedInUser::class => function ($serviceManager) {
                    return new UserService\LoggedInUser(
                        $serviceManager->get(UserFactory\User::class)
                    );
                },
                UserService\Photo\Upload::class => function ($serviceManager) {
                    return new UserService\Photo\Upload(
                        $serviceManager->get(UserTable\Photo::class)
                    );
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
                UserService\User::class => function ($serviceManager) {
                    return new UserService\User(
                        $serviceManager->get(UserTable\User::class)
                    );
                },
                UserTable\Photo::class => function ($serviceManager) {
                    return new UserTable\Photo(
                        $serviceManager->get('main')
                    );
                },
                UserTable\Post::class => function ($serviceManager) {
                    return new UserTable\Post(
                        $serviceManager->get('main')
                    );
                },
                UserTable\Register::class => function ($serviceManager) {
                    return new UserTable\Register(
                        $serviceManager->get('main')
                    );
                },
                UserTable\User::class => function ($serviceManager) {
                    return new UserTable\User(
                        $serviceManager->get('main')
                    );
                },
                UserTable\UserEmail::class => function ($serviceManager) {
                    return new UserTable\UserEmail(
                        $serviceManager->get('main')
                    );
                },
            ],
        ];
    }
}
