<?php
namespace LeoGalleguillos\User;

use LeoGalleguillos\User\Model\Service\User as UserService;
use LeoGalleguillos\User\Model\Table\User as UserTable;

class Module
{
    public function getServiceConfig()
    {
        return [
            'factories' => [
                UserService::class => function ($serviceManager) {
                    return new UserService();
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
