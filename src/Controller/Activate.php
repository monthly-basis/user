<?php
namespace MonthlyBasis\User\Controller;

use MonthlyBasis\User\Model\Service as UserService;
use MonthlyBasis\User\Model\Table as UserTable;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\Exception\InvalidQueryException;
use Laminas\Mvc\Controller\AbstractActionController;

class Activate extends AbstractActionController
{
    public function __construct(
        Adapter $adapter,
        UserTable\Register $registerTable,
        UserTable\User $userTable,
        UserTable\UserEmail $userEmailTable
    ) {
        $this->adapter        = $adapter;
        $this->registerTable  = $registerTable;
        $this->userTable      = $userTable;
        $this->userEmailTable = $userEmailTable;
    }

    public function indexAction()
    {
        $registerId     = $this->params()->fromRoute('registerId');
        $activationCode = $this->params()->fromRoute('activationCode');

        $registerArray = $this->registerTable->selectWhereRegisterIdAndActivationCode(
            $registerId,
            $activationCode
        );

        if (empty($registerArray)) {
            $url = 'https://'
                 . $_SERVER['HTTP_HOST'];
            return $this->redirect()->toUrl($url)->setStatusCode(303);
        }

        $username     = $registerArray['username'];
        $email        = $registerArray['email'];
        $passwordHash = $registerArray['password_hash'];
        $birthday     = $registerArray['birthday'];
        $gender       = $registerArray['gender'];

        $this->adapter->getDriver()->getConnection()->beginTransaction();
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
            $this->adapter->getDriver()->getConnection()->rollback();
            return;
        }
        $this->adapter->getDriver()->getConnection()->commit();
    }
}
