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

        $_SERVER['HTTP_HOST'] = 'example.com';
    }
}
