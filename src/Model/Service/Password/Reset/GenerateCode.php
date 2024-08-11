<?php
namespace LeoGalleguillos\User\Model\Service\Password\Reset;

class GenerateCode
{
    public function generateCode(): string
    {
        return bin2hex(random_bytes(32));
    }
}
