<?php
namespace MonthlyBasis\UserTest\Model\Table;

use MonthlyBasis\User\Model\Table as UserTable;
use Laminas\Db\Adapter\Adapter;
use PHPUnit\Framework\TestCase;

class RegisterTest extends TestCase
{
    /**
     * @var string
     */
    protected $sqlPath = __DIR__ . '/../../..' . '/sql/test/register/';

    /**
     * @var RegisterTable
     */
    protected $registerTable;

    protected function setUp(): void
    {
        $configArray         = require(__DIR__ . '/../../../config/autoload/local.php');
        $configArray         = $configArray['db']['adapters']['test'];
        $this->adapter       = new Adapter($configArray);
        $this->registerTable = new UserTable\Register($this->adapter);

        $this->dropTable();
        $this->createTable();
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
        $this->assertInstanceOf(UserTable\Register::class, $this->registerTable);
    }

    public function testInsert()
    {
        $this->registerTable->insert(
            '1',
            'username',
            'test@example.com',
            password_hash('123', PASSWORD_DEFAULT),
            '2018-10-22',
            'M'
        );

        $this->registerTable->insert(
            '2',
            'username2',
            'test2@example.com',
            password_hash('123', PASSWORD_DEFAULT),
            '2018-10-22',
            'F'
        );

        $this->assertSame(
            2,
            $this->registerTable->selectCount()
        );
    }

    public function testSelectCount()
    {
        $this->assertSame(
            0,
            $this->registerTable->selectCount()
        );
    }
}
