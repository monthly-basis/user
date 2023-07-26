<?php
namespace MonthlyBasis\User\Model\Table;

use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\Driver\Pdo\Result;
use Laminas\Db\Sql\Sql;
use MonthlyBasis\Laminas\Model\Db as LaminasDb;

class UserFollow extends LaminasDb\Table
{
    protected Adapter $adapter;
    protected string $table = 'user_follow';

    public function __construct(
        protected Sql $sql
    ) {
        $this->sql     = $sql;
        $this->adapter = $this->sql->getAdapter();
    }
}
