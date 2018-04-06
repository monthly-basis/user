<?php
namespace LeoGalleguillos\LoginLogTest\Model\Table;

use ArrayObject;
use Exception;
use LeoGalleguillos\User\Model\Table as UserTable;
use LeoGalleguillos\UserTest\TableTestCase;
use Zend\Db\Adapter\Adapter;
use PHPUnit\Framework\TestCase;

class LoginLogTest extends TableTestCase
{
    /**
     * @var string
     */
    protected $sqlPath = __DIR__ . '/../../..' . '/sql/leogalle_test/login_log/';

    protected function setUp()
    {
        $configArray     = require(__DIR__ . '/../../../config/autoload/local.php');
        $configArray     = $configArray['db']['adapters']['leogalle_test'];
        $this->adapter   = new Adapter($configArray);

        $this->loginLogTable      = new UserTable\LoginLog($this->adapter);

        $this->setForeignKeyChecks0();
        $this->dropTable();
        $this->createTable();
        $this->setForeignKeyChecks1();
    }

    protected function dropTable()
    {
        $sql = file_get_contents($this->sqlPath . 'drop.sql');
        $result = $this->adapter->query($sql)->execute();
    }

    protected function createTable()
    {
        $sql = file_get_contents($this->sqlPath . 'create.sql');
        $result = $this->adapter->query($sql)->execute();
    }

    public function testInitialize()
    {
        $this->assertInstanceOf(
            UserTable\LoginLog::class,
            $this->loginLogTable
        );
    }

    public function testInsertAndSelectCount()
    {
        $this->assertSame(
            0,
            $this->loginLogTable->selectCount()
        );
        $this->assertSame(
            1,
            $this->loginLogTable->insert('255.255.255.255', 0)
        );
        $this->assertSame(
            2,
            $this->loginLogTable->insert('123.123.123.123', 1)
        );
        $this->assertSame(
            2,
            $this->loginLogTable->selectCount()
        );
    }

    public function testselectCountWhereIpSuccessCreated()
    {
        $this->assertSame(
            0,
            $this->loginLogTable->selectCountWhereIpSuccessCreated('123.123.123.123')
        );
        $this->loginLogTable->insert('123.123.123.123', 1);
        $this->assertSame(
            0,
            $this->loginLogTable->selectCountWhereIpSuccessCreated('123.123.123.123')
        );
        $this->loginLogTable->insert('123.123.123.123', 0);
        $this->loginLogTable->insert('123.123.123.123', 0);
        $this->loginLogTable->insert('123.123.123.123', 0);
        $this->assertSame(
            3,
            $this->loginLogTable->selectCountWhereIpSuccessCreated('123.123.123.123')
        );
        $this->loginLogTable->insert('123.123.123.123', 1);
        $this->loginLogTable->insert('123.123.123.123', 0);
        $this->assertSame(
            4,
            $this->loginLogTable->selectCountWhereIpSuccessCreated('123.123.123.123')
        );
    }
}
