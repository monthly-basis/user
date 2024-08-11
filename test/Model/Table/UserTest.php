<?php
namespace LeoGalleguillos\UserTest\Model\Table;

use LeoGalleguillos\User\Model\Table\User as UserTable;
use Zend\Db\Adapter\Adapter;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    /**
     * @var UserTable
     */
    protected $userTable;

    public static function setUpBeforeClass()
    {
        $configArray = require(__DIR__ . '/../../../config/autoload/local.php');
        $configArray = $configArray['db']['adapters']['leogalle_test'];
        //$this->adapter = new Adapter($configArray);

    }

    protected function setUp()
    {
        // Instantiate table here.
        $adapterStub = $this->createMock(Adapter::class);
        $this->userTable = new UserTable($adapterStub);
    }

    public function testInitialize()
    {
        $this->assertInstanceOf(UserTable::class, $this->userTable);
    }
}
