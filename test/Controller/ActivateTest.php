<?php
namespace MonthlyBasis\UserTest\Controller;

use Laminas\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use MonthlyBasis\User\Controller as UserController;

class ActivateTest extends AbstractHttpControllerTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->setApplicationConfig(
            require __DIR__ . '/../../config/application.config.php'
        );
    }

    /**
     * @runInSeparateProcess
     */
    public function test_indexAction_invalidActivation()
    {
        $_SERVER['HTTP_HOST']   = 'www.example.com';
        $_SERVER['REMOTE_ADDR'] = '1.2.4.8';

        $this->dispatch('/activate/12345/invalid-activation-code', 'GET');
        $this->assertControllerName(UserController\Activate::class);
        $this->assertResponseStatusCode(303);
    }
}
