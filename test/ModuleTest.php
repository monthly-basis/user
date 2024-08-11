<?php
namespace MonthlyBasis\UserTest;

use MonthlyBasis\LaminasTest\ModuleTestCase;
use MonthlyBasis\User\Module;
use Laminas\Mvc\Application;

class ModuleTest extends ModuleTestCase
{
    protected function setUp(): void
    {
        $this->module = new Module();
    }

    public function testGetControllerConfig()
    {
        $applicationConfig = include(__DIR__ . '/../config/application.config.php');
        $this->application = Application::init($applicationConfig);
        $serviceManager    = $this->application->getServiceManager();
        $controllerManager = $serviceManager->get('ControllerManager');

        $controllerConfig  = $this->module->getControllerConfig();

        foreach ($controllerConfig['factories'] as $class => $value) {
            $this->assertInstanceOf(
                $class,
                $controllerManager->get($class)
            );
        }
    }
}
