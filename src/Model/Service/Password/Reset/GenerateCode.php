<?php
namespace LeoGalleguillos\User\Model\Service\Password\Reset;

class GenerateCode
{
    /**
     * @return string
     */
    public function generateCode(): string
    {
        $lenth = 32;
        return bin2hex(random_bytes($length / 2));
    }
}
