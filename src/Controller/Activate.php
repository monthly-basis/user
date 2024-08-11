<?php
namespace MonthlyBasis\User\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use MonthlyBasis\User\Model\Service as UserService;

/**
 * @deprecated Use Controller classes in MonthlyBasis\UserHttps module instead
 */
class Activate extends AbstractActionController
{
    public function __construct(
        UserService\Activate $activateService
    ) {
        $this->activateService = $activateService;
    }

    public function indexAction()
    {
        $registerId     = $this->params()->fromRoute('registerId');
        $activationCode = $this->params()->fromRoute('activationCode');

        if (!$this->activateService->activate($registerId, $activationCode)) {
            $url = 'https://' . $_SERVER['HTTP_HOST'];
            return $this->redirect()->toUrl($url)->setStatusCode(303);
        }
    }
}
