<?php

namespace App\Model\Location;

use App\Model\AbstractBaseDao;

class Dao extends AbstractBaseDao
{
    public function getTableName(): string
    {
        return 'locations';
    }
}
