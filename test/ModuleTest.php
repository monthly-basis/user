<?php
namespace MonthlyBasis\UserTest;

use MonthlyBasis\User\Module;
use PHPUnit\Framework\TestCase;
use Laminas\Mvc\Application;

class ModuleTest extends TestCase
{
    protected function setUp(): void
    {
        $this->module = new Module();
    }

    public function testGetConfig()
    {
        $applicationConfig = include(__DIR__ . '/../config/application.config.php');
        $this->application = Application::init($applicationConfig);
        $serviceConfig     = $this->module->getServiceConfig();
        $serviceManager    = $this->application->getServiceManager();
        $viewHelperManager = $serviceManager->get('ViewHelperManager');
        $config            = $this->module->getConfig();

        $this->assertTrue(is_array($config));

        if (isset($config['view_helpers']['aliases'])) {
            foreach ($config['view_helpers']['aliases'] as $alias => $class) {
                $this->assertInstanceOf(
                    $class,
                    $viewHelperManager->get($class)
                );
            }
        }

        if (isset($config['view_helpers']['factories'])) {
            foreach ($config['view_helpers']['factories'] as $class => $value) {
                $this->assertInstanceOf(
                    $class,
                    $viewHelperManager->get($class)
                );
            }
        }
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

    public function testGetServiceConfig()
    {
        $applicationConfig = include(__DIR__ . '/../config/application.config.php');
        $this->application = Application::init($applicationConfig);
        $serviceConfig     = $this->module->getServiceConfig();
        $serviceManager    = $this->application->getServiceManager();

        foreach ($serviceConfig['factories'] as $class => $value) {
            $this->assertInstanceOf(
                $class,
                $serviceManager->get($class)
            );
        }
    }
}
