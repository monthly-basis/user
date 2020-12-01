<?php
namespace MonthlyBasis\User\View\Helper;

use Exception;
use MonthlyBasis\User\Model\Entity as UserEntity;
use MonthlyBasis\User\Model\Service as UserService;
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
