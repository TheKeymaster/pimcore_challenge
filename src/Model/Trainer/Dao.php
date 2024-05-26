<?php

namespace App\Model\Trainer;

use App\Model\AbstractBaseDao;

class Dao extends AbstractBaseDao
{
    public function getTableName(): string
    {
        return 'trainers';
    }
}
