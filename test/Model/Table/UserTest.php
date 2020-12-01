<?php
namespace MonthlyBasis\UserTest\Model\Table;

use ArrayObject;
use Exception;
use MonthlyBasis\User\Model\Table as UserTable;
use MonthlyBasis\LaminasTest\TableTestCase;
use Laminas\Db\Adapter\Adapter;
use PHPUnit\Framework\TestCase;

class UserTest extends TableTestCase
{
    protected function setUp(): void
    {
        $this->userTable      = new UserTable\User($this->getAdapter());
        $this->loginHashTable = new UserTable\User\LoginHash($this->getAdapter());
        $this->loginIpTable   = new UserTable\User\LoginIp($this->getAdapter());

        $this->setForeignKeyChecks0();
        $this->dropTable('user');
        $this->createTable('user');
        $this->setForeignKeyChecks1();
    }

    public function testInsert()
    {
        $this->userTable->insert(
            'username',
            'password hash',
            '1983-10-22',
            'M'
        );

        $this->userTable->insert(
            'LeoGalleguillos',
            'abcdefg1234567890',
            '1983-10-22',
            'M'
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

    public function testSelectWhereUserId()
    {
        $this->userTable->insert(
            'LeoGalleguillos',
            'abcdefg1234567890',
            '1983-10-22',
            'M'
        );
        $this->assertIsArray(
            $this->userTable->selectWhereUserId(1)
        );
    }

    public function testSelectWhereUserIdLoginHashLoginIp()
    {
        try {
            $this->userTable->selectWhereUserIdLoginHash(
                1,
                'login-hash'
            );
            $this->fail();
        } catch (Exception $exception) {
            $this->assertSame(
                $exception->getMessage(),
                'Row with user ID and login hash not found.'
            );
        }

        $this->userTable->insert(
            'username',
            'password-hash',
            '1983-10-22',
            'M'
        );
        $this->loginHashTable->updateWhereUserId(
            'login-hash',
            1
        );
        $this->loginIpTable->updateWhereUserId(
            'login-ip',
            1
        );
        $array = $this->userTable->selectWhereUserIdLoginHash(
            1,
            'login-hash'
        );
        $this->assertSame(
            'username',
            $array['username']
        );
        $this->assertSame(
            'password-hash',
            $array['password_hash']
        );
    }

    public function testSelectWhereUsername()
    {
        $this->userTable->insert(
            'LeoGalleguillos',
            'abcdefg1234567890',
            '1983-10-22',
            'M'
        );
        $this->assertIsArray(
            $this->userTable->selectWhereUsername('LeoGalleguillos')
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
