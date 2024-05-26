<?php

namespace App\Model\Team;

use App\Model\AbstractBaseDao;

class Dao extends AbstractBaseDao
{
    public function getTableName(): string
    {
        return 'teams';
    }
}
