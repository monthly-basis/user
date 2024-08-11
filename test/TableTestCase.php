<?php
namespace LeoGalleguillos\UserTest;

use Laminas\Db\Adapter\Adapter;
use PHPUnit\Framework\TestCase;

class TableTestCase extends TestCase
{
    protected $adapter;

    /**
     * @var string
     */
    protected $sqlDirectory = __DIR__ . '/../sql';

    protected function getAdapter() : Adapter
    {
        if (isset($this->adapter)) {
            return $this->adapter;
        }

        $this->adapter = new Adapter($this->getConfigArray());
        return $this->adapter;
    }

    protected function getConfigArray() : array
    {
        $configArray = require($_SERVER['PWD'] . '/config/autoload/local.php');
        return $configArray['db']['adapters']['test'];
    }

    protected function setForeignKeyChecks0()
    {
        $sql = file_get_contents(
            $this->sqlDirectory . '/SetForeignKeyChecks0.sql'
        );

        $result = $this->adapter->query($sql)->execute();
    }

    protected function setForeignKeyChecks1()
    {
        $sql = file_get_contents(
            $this->sqlDirectory . '/SetForeignKeyChecks1.sql'
        );

        $result = $this->adapter->query($sql)->execute();
    }
}
