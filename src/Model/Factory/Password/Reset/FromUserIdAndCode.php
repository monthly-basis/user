<?php
namespace MonthlyBasis\User\Model\Factory\Password\Reset;

use Exception;
use MonthlyBasis\User\Model\Entity as UserEntity;
use MonthlyBasis\User\Model\Factory as UserFactory;
use MonthlyBasis\User\Model\Table as UserTable;

class FromUserIdAndCode
{
    public function __construct(
        UserFactory\Password\Reset\FromArray $fromArrayFactory,
        UserTable\ResetPassword $resetPasswordTable
    ) {
        $this->fromArrayFactory   = $fromArrayFactory;
        $this->resetPasswordTable = $resetPasswordTable;
    }

    /**
     * @throws Exception
     */
    public function buildFromUserIdAndCode(
        int $userId,
        string $code
    ): UserEntity\Password\Reset {
        $result = $this->resetPasswordTable->selectWhereUserIdAndCode(
            $userId,
            $code,
        );
        $array = $result->current();

        if (empty($array)) {
            throw new Exception('Reset entity cannot be built.');
        }

        $resetEntity = $this->fromArrayFactory->buildFromArray($array);

        return $resetEntity;
    }
}
