<?php
namespace LeoGalleguillos\User\View\Helper\Factory;

use LeoGalleguillos\User\Model\Factory as UserFactory;
use Zend\View\Helper\AbstractHelper;

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
