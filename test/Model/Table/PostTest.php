<?php
namespace LeoGalleguillos\UserTest\Model\Table;

use ArrayObject;
use LeoGalleguillos\User\Model\Table as UserTable;
use LeoGalleguillos\UserTest\TableTestCase;
use Zend\Db\Adapter\Adapter;
use PHPUnit\Framework\TestCase;
use Zend\Db\Adapter\Exception\InvalidQueryException;

class PostTest extends TableTestCase
{
    /**
     * @var string
     */
    protected $sqlPath = __DIR__ . '/../../..' . '/sql/leogalle_test/post/';

    protected function setUp()
    {
        $configArray     = require(__DIR__ . '/../../../config/autoload/local.php');
        $configArray     = $configArray['db']['adapters']['leogalle_test'];
        $this->adapter   = new Adapter($configArray);

        $this->setForeignKeyChecks0();
        $this->dropTables();
        $this->createTables();
        $this->setForeignKeyChecks1();

        $this->postTable = new UserTable\Post($this->adapter);
        $this->userTable = new UserTable\User($this->adapter);
    }

    protected function dropTables()
    {
        $sql = file_get_contents($this->sqlDirectory . '/leogalle_test/user/drop.sql');
        $result = $this->adapter->query($sql)->execute();

        $sql = file_get_contents($this->sqlDirectory . '/leogalle_test/post/drop.sql');
        $result = $this->adapter->query($sql)->execute();
    }

    protected function createTables()
    {
        $sql = file_get_contents($this->sqlDirectory . '/leogalle_test/user/create.sql');
        $result = $this->adapter->query($sql)->execute();

        $sql = file_get_contents($this->sqlDirectory . '/leogalle_test/post/create.sql');
        $result = $this->adapter->query($sql)->execute();
    }

    public function testInitialize()
    {
        $this->assertInstanceOf(UserTable\Post::class, $this->postTable);
    }

    public function testInsert()
    {
        try {
            $this->postTable->insert(1, 2, 'message');
            $this->fail();
        } catch (InvalidQueryException $exception) {
            $this->assertInstanceOf(InvalidQueryException::class, $exception);
        }

        $this->userTable->insert(
            'username',
            'password hash',
            'full name'
        );

        try {
            $this->postTable->insert(1, 2, 'message');
            $this->fail();
        } catch (InvalidQueryException $exception) {
            $this->assertInstanceOf(InvalidQueryException::class, $exception);
        }

        $this->userTable->insert(
            'another_username',
            'password hash',
            'another full name'
        );

        $this->assertSame(
            3,
            $this->postTable->insert(1, 2, 'message')
        );
    }

    public function testSelectCount()
    {
        $this->assertSame(
            0,
            $this->postTable->selectCount()
        );
    }

    public function testSelectWhereToUserId()
    {
        $this->assertEmpty(
            $this->postTable->selectWhereToUserId(2)
        );

        $this->userTable->insert(
            'username',
            'password hash'
        );
        $this->userTable->insert(
            'username2',
            'password hash'
        );
        $this->userTable->insert(
            'username3',
            'password hash'
        );
        $this->postTable->insert(1, 2, 'message');
        $this->postTable->insert(3, 2, 'another message');

        $arrayObjects = new ArrayObject([
            new ArrayObject([
                'post_id' => '1',
                'from_user_user_id' => '1',
                'from_user_username' => 'username',
                'to_user_user_id' => '2',
                'to_user_username' => 'username2',
                'message' => 'message',
            ]),
            new ArrayObject([
                'post_id' => '2',
                'from_user_user_id' => '3',
                'from_user_username' => 'username3',
                'to_user_user_id' => '2',
                'to_user_username' => 'username2',
                'message' => 'another message',
            ]),
        ]);

        $this->assertEquals(
            $arrayObjects[0]['to_user_user_id'],
            $this->postTable->selectWhereToUserId(2)[0]['to_user_user_id']
        );

        $this->assertEquals(
            $arrayObjects[1]['message'],
            $this->postTable->selectWhereToUserId(2)[1]['message']
        );

        $this->assertEmpty(
            $this->postTable->selectWhereToUserId(1),
            $this->postTable->selectWhereToUserId(3)
        );
    }
}
