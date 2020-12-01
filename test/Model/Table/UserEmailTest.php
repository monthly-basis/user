<?php
namespace MonthlyBasis\UserTest\Model\Table;

use MonthlyBasis\User\Model\Table as UserTable;
use Laminas\Db\Adapter\Adapter;
use PHPUnit\Framework\TestCase;

class UserEmailTest extends TestCase
{
    /**
     * @var string
     */
    protected $sqlPath = __DIR__ . '/../../..' . '/sql/test/user_email/';

    /**
     * @var UserTable\UserEmail
     */
    protected $userEmailTable;

    protected function setUp(): void
    {
        $configArray          = require(__DIR__ . '/../../../config/autoload/local.php');
        $configArray          = $configArray['db']['adapters']['test'];
        $this->adapter        = new Adapter($configArray);
        $this->userEmailTable = new UserTable\UserEmail($this->adapter);

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
        $this->assertInstanceOf(UserTable\UserEmail::class, $this->userEmailTable);
    }

    public function testInsert()
    {
        $this->userEmailTable->insert(
            '1',
            'test@example.com'
        );

        $this->userEmailTable->insert(
            '2',
            'test2@example.com'
        );

        $this->assertSame(
            2,
            $this->userEmailTable->selectCount()
        );
    }

    public function testSelectCount()
    {
        $this->assertSame(
            0,
            $this->userEmailTable->selectCount()
        );
    }
}
