<?php
namespace LeoGalleguillos\User;

use LeoGalleguillos\Flash\Model\Service as FlashService;
use LeoGalleguillos\User\Model\Factory as UserFactory;
use LeoGalleguillos\User\Model\Service as UserService;
use LeoGalleguillos\User\Model\Table as UserTable;

class Module
{
    public function getConfig()
    {
        return [];
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
                UserService\Posts::class => function ($serviceManager) {
                    return new UserService\Posts(
                        $serviceManager->get(UserFactory\Post::class),
                        $serviceManager->get(UserTable\Post::class)
                    );
                },
                UserService\User::class => function ($serviceManager) {
                    return new UserService\User();
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
