<?php
namespace LeoGalleguillos\User\View\Helper;

use Exception;
use LeoGalleguillos\User\Model\Entity as UserEntity;
use LeoGalleguillos\User\Model\Service as UserService;
use Laminas\View\Helper\AbstractHelper;

class LoggedInUser extends AbstractHelper
{
    public function __construct(
        UserService\LoggedInUser $loggedInUserService
    ) {
        $this->loggedInUserService = $loggedInUserService;
    }

    /**
     * Invoke.
     *
     * Gets logged in user.
     *
     * @throws Exception
     * @return UserEntity\User
     */
    public function __invoke() : UserEntity\User
    {
        return $this->loggedInUserService->getLoggedInUser();
    }
}
