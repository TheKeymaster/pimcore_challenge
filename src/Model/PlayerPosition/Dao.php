<?php

namespace App\Model\PlayerPosition;

use App\Model\AbstractBaseDao;

class Dao extends AbstractBaseDao
{
    public function getTableName(): string
    {
        return 'player_positions';
    }
}
