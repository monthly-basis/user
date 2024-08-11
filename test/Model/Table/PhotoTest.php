<?php
namespace LeoGalleguillos\UserTest\Model\Table;

use ArrayObject;
use LeoGalleguillos\User\Model\Table as UserTable;
use LeoGalleguillos\UserTest\TableTestCase;
use Zend\Db\Adapter\Adapter;
use PHPUnit\Framework\TestCase;

class PhotoTest extends TableTestCase
{
    /**
     * @var string
     */
    protected $sqlPath = __DIR__ . '/../../../sql/leogalle_test/photo/';

    /**
     * @var UserTable
     */
    protected $photoTable;

    protected function setUp()
    {
        $configArray     = require(__DIR__ . '/../../../config/autoload/local.php');
        $configArray     = $configArray['db']['adapters']['leogalle_test'];
        $this->adapter   = new Adapter($configArray);
        $this->photoTable = new UserTable\Photo($this->adapter);

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
            UserTable\Photo::class,
            $this->photoTable
        );
    }

    public function testInsert()
    {
        $this->assertSame(
            1,
            $this->photoTable->insert(123, 'jpg', 'title', 'description')
        );

        $this->assertSame(
            2,
            $this->photoTable->insert(123, 'jpg', 't', 'd')
        );
    }

    public function testSelectCount()
    {
        $this->assertSame(
            0,
            $this->photoTable->selectCount()
        );
    }
}
