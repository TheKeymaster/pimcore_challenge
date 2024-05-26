<?php

namespace App\Model\Player;

use App\Model\AbstractBaseDao;

class Dao extends AbstractBaseDao
{
    public function getTableName(): string
    {
        return 'players';
    }
}
