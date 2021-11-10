<?php
namespace MonthlyBasis\UserTest\Model\Table;

use ArrayObject;
use Exception;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\Driver\Pdo\Result;
use MonthlyBasis\LaminasTest\TableTestCase;
use MonthlyBasis\User\Model\Table as UserTable;
use MonthlyBasis\User\Model\Db as UserDb;
use PHPUnit\Framework\TestCase;

class UserTest extends TableTestCase
{
    protected function setUp(): void
    {
        $this->sql = new UserDb\Sql(
            $this->getAdapter()
        );

        $this->userTable    = new UserTable\User($this->sql);
        $this->userIdTable  = new UserTable\User\UserId($this->sql);

        $this->setForeignKeyChecks0();
        $this->dropAndCreateTable('user');
        $this->setForeignKeyChecks1();
    }

    public function test_getColumns_result()
    {
        $select = $this->sql
             ->select()
             ->columns($this->userTable->getColumns())
             ->from('user');
        $result = $this->sql->prepareStatementForSqlObject($select)->execute();

        $this->assertInstanceOf(
            Result::class,
            $result
        );
    }

    public function test_insert()
    {
        $userId = $this->userTable->insert(
            'username',
            'password hash',
            '1983-10-22',
            'M'
        );
        $this->assertSame(
            1,
            $userId,
        );

        $userId = $this->userTable->insert(
            'LeoGalleguillos',
            'abcdefg1234567890',
            '1983-10-22',
            'M'
        );
        $this->assertSame(
            2,
            $userId,
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
            '1983-10-22',
            'M'
        );
        $this->userTable->insert(
            'Username',
            'passwordhash12345',
            '1983-10-22',
            'M'
        );
        $generator = $this->userTable->selectOrderByCreatedDesc();
        foreach ($generator as $array) {
            $this->assertIsArray(
                $array
            );
        }
    }

    public function test_selectWhereUserId()
    {
        $this->assertEmpty(
            $this->userTable->selectWhereUserId(1)
        );

        $this->userTable->insert(
            'LeoGalleguillos',
            'abcdefg1234567890',
            '1983-10-22',
            'M'
        );
        $array = $this->userTable->selectWhereUserId(1)->current();
        $this->assertSame(
            [
                'user_id'       => '1',
                'username'      => 'LeoGalleguillos',
                'password_hash' => 'abcdefg1234567890',
            ],
            [
                'user_id'       => $array['user_id'],
                'username'      => $array['username'],
                'password_hash' => $array['password_hash'],
            ]
        );
    }

    public function test_selectWhereUsername()
    {
        $this->assertEmpty(
            $result = $this->userTable->selectWhereUsername('LeoGalleguillos')
        );
        $this->userTable->insert(
            'LeoGalleguillos',
            'abcdefg1234567890',
            '1983-10-22',
            'M'
        );
        $result = $this->userTable->selectWhereUsername('LeoGalleguillos');
        $array = $result->current();
        $this->assertSame(
            [
                'user_id' => '1',
                'username' => 'LeoGalleguillos',
            ],
            [
                'user_id' => $array['user_id'],
                'username' => $array['username'],
            ]
        );
    }

    public function testUpdateViewsWhereUserId()
    {
        $this->userTable->insert(
            'LeoGalleguillos',
            'abcdefg1234567890',
            '1983-10-22',
            'M'
        );
        $this->assertTrue(
            $this->userTable->updateViewsWhereUserId(1),
            $this->userTable->updateViewsWhereUserId(1),
            $this->userTable->updateViewsWhereUserId(1)
        );

        $array = $this->userTable->selectWhereUserId(1)->current();

        $this->assertSame(
            $array['views'],
            '3'
        );
    }

    public function testUpdateWhereUserId()
    {
        $this->userTable->insert(
            'LeoGalleguillos',
            '$2y$10$u.zUjyq1akFSMW5WzTa8Iurj3WjVXrrWZUaZREukilchH6s8Kcq1O',
            '1983-10-22',
            'M'
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
