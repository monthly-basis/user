<?php
namespace MonthlyBasis\User\Model\Service\Password;

class Valid
{
    public function isValid(
        string $password,
        string $passwordHash
    ): bool {
        return password_verify($password, $passwordHash);
    }
}
