<?php
namespace LeoGalleguillos\UserTest\Model\Table;

use LeoGalleguillos\User\Model\Table as UserTable;
use LeoGalleguillos\UserTest\TableTestCase;
use PHPUnit\Framework\TestCase;
use Zend\Db\Adapter\Adapter;

class ResetPasswordTest extends TableTestCase
{
    /**
     * @var string
     */
    protected $sqlPath;

    protected function setUp(): void
    {
        $this->sqlPath       = $_SERVER['PWD'] . '/sql/test/reset_password/';
        $this->adapter       = $this->getAdapter();
        $this->resetPasswordTable = new UserTable\ResetPassword($this->adapter);

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
        $this->assertInstanceOf(
            UserTable\ResetPassword::class,
            $this->resetPasswordTable
        );
    }
}
