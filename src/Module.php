<?php
namespace LeoGalleguillos\User;

use LeoGalleguillos\User\Model\Service\User as UserService;

class Module
{
    public function getServiceConfig()
    {
        return [
            'factories' => [
                UserService::class => function ($serviceManager) {
                    return new UserService();
                },
            ],
        ];
    }
}
