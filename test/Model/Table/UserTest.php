<?php
namespace LeoGalleguillos\UserTest\Model\Table;

use ArrayObject;
use LeoGalleguillos\User\Model\Table\User as UserTable;
use LeoGalleguillos\UserTest\TableTestCase;
use Zend\Db\Adapter\Adapter;
use PHPUnit\Framework\TestCase;

class UserTest extends TableTestCase
{
    /**
     * @var string
     */
    protected $sqlPath = __DIR__ . '/../../..' . '/sql/leogalle_test/user/';

    /**
     * @var UserTable
     */
    protected $userTable;

    protected function setUp()
    {
        $configArray     = require(__DIR__ . '/../../../config/autoload/local.php');
        $configArray     = $configArray['db']['adapters']['leogalle_test'];
        $this->adapter   = new Adapter($configArray);
        $this->userTable = new UserTable($this->adapter);

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
        $this->assertInstanceOf(UserTable::class, $this->userTable);
    }

    public function testInsert()
    {
        $this->userTable->insert(
            'username',
            'password hash',
            'full name'
        );

        $this->userTable->insert(
            'LeoGalleguillos',
            'abcdefg1234567890',
            'Leo Galleguillos'
        );

        $this->assertSame(
            2,
            $this->userTable->selectCount()
        );
    }

    public function testSelectCount()
    {
        $this->assertSame(
            0,
            $this->userTable->selectCount()
        );
    }

    public function testSelectOrderByCreatedDesc()
    {
        $this->userTable->insert(
            'LeoGalleguillos',
            'abcdefg1234567890',
            'Leo Galleguillos'
        );
        $this->userTable->insert(
            'Username',
            'passwordhash12345'
        );
        $generator = $this->userTable->selectOrderByCreatedDesc();
        foreach ($generator as $array) {
            $this->assertInternalType(
                'array',
                $array
            );
        }
    }

    public function testSelectWhereUserId()
    {
        $this->userTable->insert(
            'LeoGalleguillos',
            'abcdefg1234567890',
            'Leo Galleguillos'
        );
        $this->assertInternalType(
            'array',
            $this->userTable->selectWhereUserId(1)
        );
    }

    public function testSelectWhereUsername()
    {
        $this->userTable->insert(
            'LeoGalleguillos',
            'abcdefg1234567890',
            'Leo Galleguillos'
        );
        $this->assertInstanceOf(
            ArrayObject::class,
            $this->userTable->selectWhereUsername('LeoGalleguillos')
        );
    }

    public function testUpdateViewsWhereUserId()
    {
        $this->userTable->insert(
            'LeoGalleguillos',
            'abcdefg1234567890',
            'Leo Galleguillos'
        );
        $this->assertTrue(
            $this->userTable->updateViewsWhereUserId(1),
            $this->userTable->updateViewsWhereUserId(1),
            $this->userTable->updateViewsWhereUserId(1)
        );

        $arrayObject = $this->userTable->selectWhereUserId(1);

        $this->assertSame(
            $arrayObject['views'],
            '3'
        );
    }

    public function testUpdateWhereUserId()
    {
        $this->userTable->insert(
            'LeoGalleguillos',
            'abcdefg1234567890',
            'Leo Galleguillos'
        );

        $arrayObject = new ArrayObject([
            'welcome_message' => 'My welcome message.',
        ]);

        $this->assertTrue(
            $this->userTable->updateWhereUserId($arrayObject, 1)
        );
        $this->assertFalse(
            $this->userTable->updateWhereUserId($arrayObject, 1)
        );
    }
}
