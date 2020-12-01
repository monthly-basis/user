<?php
namespace MonthlyBasis\User\Model\Service\Password\Reset;

class GenerateCode
{
    /**
     * @return string
     */
    public function generateCode(): string
    {
        $length = 32;
        return bin2hex(random_bytes($length / 2));
    }
}
