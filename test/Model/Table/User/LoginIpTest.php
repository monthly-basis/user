<?php
namespace LeoGalleguillos\UserTest\Model\Table\User;

use LeoGalleguillos\User\Model\Table as UserTable;
use LeoGalleguillos\UserTest\TableTestCase;
use Zend\Db\Adapter\Adapter;

class LoginIpTest extends TableTestCase
{
    /**
     * @var string
     */
    protected $sqlPath = __DIR__ . '/../../../..' . '/sql/test/user/';

    /**
     * @var UserTable
     */
    protected $userTable;

    protected function setUp(): void
    {
        $configArray     = require(__DIR__ . '/../../../../config/autoload/local.php');
        $configArray     = $configArray['db']['adapters']['test'];
        $this->adapter   = new Adapter($configArray);

        $this->userTable = new UserTable\User($this->adapter);
        $this->loginIpTable = new UserTable\User\LoginIp($this->adapter);

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
            UserTable\User\LoginIp::class,
            $this->loginIpTable
        );
    }

    public function testUpdateWhereUsername()
    {
        $this->assertFalse(
            $this->loginIpTable->updateWhereUsername('123.456.789.012', 'username')
        );

        $this->userTable->insert(
            'username',
            'password hash',
            '1983-10-22',
            'M'
        );
        $this->assertTrue(
            $this->loginIpTable->updateWhereUsername('123.456.789.012', 'username')
        );
    }
}
