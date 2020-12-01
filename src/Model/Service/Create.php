<?php
namespace MonthlyBasis\User\Model\Service;

use DateTime;
use MonthlyBasis\User\Model\Entity as UserEntity;
use MonthlyBasis\User\Model\Factory as UserFactory;
use MonthlyBasis\User\Model\Table as UserTable;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\Exception\InvalidQueryException;
use Laminas\Mvc\Controller\AbstractActionController;

class Create extends AbstractActionController
{
    public function __construct(
        Adapter $adapter,
        UserFactory\User $userFactory,
        UserTable\User $userTable,
        UserTable\UserEmail $userEmailTable
    ) {
        $this->adapter        = $adapter;
        $this->userFactory    = $userFactory;
        $this->userTable      = $userTable;
        $this->userEmailTable = $userEmailTable;
    }

    public function create(
        DateTime $birthday,
        string $email = null,
        string $gender = null,
        string $passwordHash,
        string $username
    ): UserEntity\User {
        $this->adapter->getDriver()->getConnection()->beginTransaction();

        try {
            $userId = $this->userTable->insert(
                $username,
                $passwordHash,
                $birthday->format('Y-m-d H:i:s'),
                $gender
            );
            if (isset($email)) {
                $this->userEmailTable->insert(
                    $userId,
                    $email
                );
            }
        } catch (InvalidQueryException $exception) {
            $this->adapter->getDriver()->getConnection()->rollback();
        }

        $this->adapter->getDriver()->getConnection()->commit();

        return $this->userFactory->buildFromUserId($userId);
    }
}
