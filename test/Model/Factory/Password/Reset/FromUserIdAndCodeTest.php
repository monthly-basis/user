<?php
namespace MonthlyBasis\UserTest\Model\Factory\Password\Reset;

use DateTime;
use Exception;
use Laminas\Db\Adapter\Driver\Pdo\Result;
use MonthlyBasis\LaminasTest\Hydrator;
use MonthlyBasis\User\Model\Entity as UserEntity;
use MonthlyBasis\User\Model\Factory as UserFactory;
use MonthlyBasis\User\Model\Table as UserTable;
use PHPUnit\Framework\TestCase;

class FromUserIdAndCodeTest extends TestCase
{
    protected function setUp(): void
    {
        $this->fromArrayFactoryMock = $this->createMock(
            UserFactory\Password\Reset\FromArray::class
        );
        $this->resetPasswordTableMock = $this->createMock(
            UserTable\ResetPassword::class
        );

        $this->fromUserIdAndCodeFactory = new UserFactory\Password\Reset\FromUserIdAndCode(
            $this->fromArrayFactoryMock,
            $this->resetPasswordTableMock,
        );

        $this->countableIteratorHydrator   = new Hydrator\CountableIterator();
        $this->resultMock = $this->createMock(
            Result::class
        );
    }

    public function test_buildFromUserIdAndCode_tableModelReturnsEmptyResult_methodThrowsException()
    {
        $this->countableIteratorHydrator->hydrate(
            $this->resultMock,
            [],
        );
        $this->resetPasswordTableMock
             ->expects($this->once())
             ->method('selectWhereUserIdAndCode')
             ->with(12345, 'the-code')
             ->willReturn($this->resultMock)
             ;

        try {
            $this->fromUserIdAndCodeFactory->buildFromUserIdAndCode(12345, 'the-code');
            $this->fail();
        } catch (Exception $exception) {
            $this->assertSame(
                'Reset entity cannot be built.',
                $exception->getMessage(),
            );
        }
    }

    public function test_buildFromUserIdAndCode_tableModelReturnsArray_resetEntityIsBuilt()
    {
        $this->countableIteratorHydrator->hydrate(
            $this->resultMock,
            [0 => ['key' => 'value']],
        );
        $this->resetPasswordTableMock
             ->expects($this->once())
             ->method('selectWhereUserIdAndCode')
             ->with(12345, 'the-code')
             ->willReturn($this->resultMock)
             ;

        $this->fromUserIdAndCodeFactory->buildFromUserIdAndCode(12345, 'the-code');
    }
}
