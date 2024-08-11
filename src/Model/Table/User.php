<?php
namespace LeoGalleguillos\User\Model\Table;

use Zend\Db\Adapter\Adapter;

class User
{
    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }
}
