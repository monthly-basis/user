<?php
namespace MonthlyBasis\User\View\Helper\Factory;

use MonthlyBasis\User\Model\Factory as UserFactory;
use Laminas\View\Helper\AbstractHelper;

class User extends AbstractHelper
{
    public function __construct(
        UserFactory\User $userFactory
    ) {
        $this->userFactory = $userFactory;
    }

    public function __invoke()
    {
        return $this->userFactory;
    }
}
