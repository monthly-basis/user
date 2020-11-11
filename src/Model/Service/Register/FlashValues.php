<?php
namespace LeoGalleguillos\User\Model\Service\Register;

use MonthlyBasis\Flash\Model\Service as FlashService;

class FlashValues
{
    public function __construct(
        FlashService\Flash $flashService
    ) {
        $this->flashService = $flashService;
    }

    public function setFlashValues()
    {
        $keys = [
            'email',
            'username',
            'password',
            'confirm-password',
            'birthday-month',
            'birthday-day',
            'birthday-year',
            'gender',
        ];
        foreach ($keys as $key) {
            $this->flashService->set(
                $key,
                $_POST[$key] ?? null
            );
        }
    }
}
