<?php
namespace LeoGalleguillos\User\Model\Service;

use Exception;
use LeoGalleguillos\User\Model\Factory as UserFactory;

class LoggedInUser
{
    public function __construct(
        UserFactory\User $userFactory
    ) {
        $this->userFactory = $userFactory;
    }

    /**
     * Get logged in user.
     *
     * @throws Exception
     * @return UserEntity\User
     */
    public function getLoggedInUser() : UserEntity\User
    {
        $userEntity =  $this->userFactory->buildFromUsername(
            $_SESSION['username']
        );

        if (!$userEntity) {
            throw new Exception('User is not logged in.');
        }

        return $userEntity;
    }
}
