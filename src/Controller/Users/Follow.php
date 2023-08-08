<?php
namespace MonthlyBasis\User\Controller\Users;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\MvcEvent;
use MonthlyBasis\User\Model\Exception as UserException;
use MonthlyBasis\User\Model\Factory as UserFactory;
use MonthlyBasis\User\Model\Service as UserService;

class Follow extends AbstractActionController
{
    public function __construct(
        protected UserFactory\User $userFactory,
        protected UserService\LoggedInUser $loggedInUserService,
        protected UserService\Url $urlService,
    ) {
    }

    public function followAction()
    {
        try {
            $userEntity1 = $this->loggedInUserService->getLoggedInUser();
        } catch (UserException $userException) {
            return $this->redirect()->toRoute('monthly-basis/user/login')->setStatusCode(303);
        }

        try {
            $userEntity2 = $this->userFactory->buildFromUserId(
                intval($this->params()->fromPost('user-id'))
            );
        } catch (UserException $userException) {
            return $this->redirect()->toRoute('monthly-basis/user/login')->setStatusCode(303);
        }

        $this->followService->follow(
            $userEntity1,
            $userEntity2,
        );

        $url = $this->urlService->getUrl($userEntity2);
        return $this->redirect()->toUrl($url)->setStatusCode(303);
    }
}
