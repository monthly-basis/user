<?php
namespace LeoGalleguillos\User\Model\Service;

use DateTime;
use LeoGalleguillos\User\Model\Table as UserTable;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\Exception\InvalidQueryException;
use Zend\Mvc\Controller\AbstractActionController;

class Create extends AbstractActionController
{
    public function __construct(
        Adapter $adapter,
        UserTable\User $userTable,
        UserTable\UserEmail $userEmailTable
    ) {
        $this->adapter        = $adapter;
        $this->userTable      = $userTable;
        $this->userEmailTable = $userEmailTable;
    }

    public function create(
        DateTime $birthday,
        string $email,
        string $gender,
        string $passwordHash,
        string $username
    ) {
        $this->adapter->getDriver()->getConnection()->beginTransaction();
        try {
            $userId = $this->userTable->insert(
                $username,
                $passwordHash,
                $birthday->format('Y-m-d H:i:s'),
                $gender
            );
            $this->userEmailTable->insert(
                $userId,
                $email
            );
        } catch (InvalidQueryException $exception) {
            $this->adapter->getDriver()->getConnection()->rollback();
            return;
        }
        $this->adapter->getDriver()->getConnection()->commit();
    }
}
