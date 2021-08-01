<?php
namespace MonthlyBasis\User\Model\Service;

use Laminas\Db\Adapter\Driver\Pdo\Connection;
use Laminas\Db\Adapter\Exception\InvalidQueryException;
use MonthlyBasis\User\Model\Table as UserTable;

class Activate
{
    public function __construct(
        Connection $connection,
        UserTable\ActivateLog $activateLogTable,
        UserTable\Register $registerTable,
        UserTable\User $userTable,
        UserTable\UserEmail $userEmailTable
    ) {
        $this->connection       = $connection;
        $this->activateLogTable = $activateLogTable;
        $this->registerTable    = $registerTable;
        $this->userTable        = $userTable;
        $this->userEmailTable   = $userEmailTable;
    }

    public function activate(int $registerId, string $activationCode): bool
    {
        $result = $this->registerTable->selectWhereRegisterIdAndActivationCode(
            $registerId,
            $activationCode
        );

        if (false == ($registerArray = $result->current())) {
            $this->activateLogTable->insert(
                $_SERVER['REMOTE_ADDR'],
                false,
            );
            return false;
        }

        $username     = $registerArray['username'];
        $email        = $registerArray['email'];
        $passwordHash = $registerArray['password_hash'];
        $birthday     = $registerArray['birthday'];
        $gender       = $registerArray['gender'];

        $this->connection->beginTransaction();
        try {
            $userId = $this->userTable->insert(
                $username,
                $passwordHash,
                $birthday,
                $gender
            );
            $this->userEmailTable->insert(
                $userId,
                $email
            );
        } catch (InvalidQueryException $exception) {
            $this->connection->rollback();
            return false;
        }
        $this->connection->commit();

        $this->activateLogTable->insert(
            $_SERVER['REMOTE_ADDR'],
            true,
        );

        return true;
    }
}
