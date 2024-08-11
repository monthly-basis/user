<?php
namespace MonthlyBasis\UserTest\Model\Table;

use ArrayObject;
use MonthlyBasis\LaminasTest\TableTestCase;
use MonthlyBasis\User\Model\Db as UserDb;
use MonthlyBasis\User\Model\Table as UserTable;
use Laminas\Db\Adapter\Exception\InvalidQueryException;

class PostTest extends TableTestCase
{
    protected function setUp(): void
    {
        $this->setForeignKeyChecks0();
        $this->dropTables(['post', 'user']);
        $this->createTables(['post', 'user']);
        $this->setForeignKeyChecks1();

        $this->sql = new UserDb\Sql(
            $this->getAdapter()
        );

        $this->postTable = new UserTable\Post($this->getAdapter());
        $this->userTable = new UserTable\User($this->sql);
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
            '1983-10-22',
            'M'
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
            '1983-10-22',
            'M'
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
            'password hash',
            '1983-10-22',
            'M'
        );
        $this->userTable->insert(
            'username2',
            'password hash',
            '1983-10-22',
            'M'
        );
        $this->userTable->insert(
            'username3',
            'password hash',
            '1983-10-22',
            'M'
        );
        $this->postTable->insert(1, 2, 'message');
        $this->postTable->insert(3, 2, 'another message');

        $arrayObjects = new ArrayObject([
            new ArrayObject([
                'post_id' => '2',
                'from_user_user_id' => '3',
                'from_user_username' => 'username3',
                'to_user_user_id' => '2',
                'to_user_username' => 'username2',
                'message' => 'another message',
            ]),
            new ArrayObject([
                'post_id' => '1',
                'from_user_user_id' => '1',
                'from_user_username' => 'username',
                'to_user_user_id' => '2',
                'to_user_username' => 'username2',
                'message' => 'message',
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
            $this->postTable->selectWhereToUserId(1)
        );
        $this->assertEmpty(
            $this->postTable->selectWhereToUserId(3)
        );
    }
}
