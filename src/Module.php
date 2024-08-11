<?php
namespace LeoGalleguillos\User;

use LeoGalleguillos\Flash\Model\Service as FlashService;
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
                UserService\Register::class => function ($serviceManager) {
                    return new UserService\Register(
                        $serviceManager->get(FlashService\Flash::class)
                    );
                },
                UserService\User::class => function ($serviceManager) {
                    return new UserService\User();
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
            ],
        ];
    }
}
