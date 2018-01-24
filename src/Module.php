<?php
namespace LeoGalleguillos\User;

use LeoGalleguillos\Flash\Model\Service as FlashService;
use LeoGalleguillos\User\Model\Service as UserService;
use LeoGalleguillos\User\Model\Table\User as UserTable;

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
                UserTable::class => function ($serviceManager) {
                    return new UserTable(
                        $serviceManager->get('main')
                    );
                },
            ],
        ];
    }
}
