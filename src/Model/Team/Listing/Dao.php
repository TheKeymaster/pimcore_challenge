<?php

namespace App\Model\Team\Listing;

use App\Model;
use Pimcore\Model\Listing\Dao\AbstractDao;

/**
 * @property Model\Team $model
 */
class Dao extends AbstractDao
{
    public function load(): array
    {
        $sql = 'SELECT id FROM ' . 'teams' . $this->getCondition() . $this->getOrder() . $this->getOffsetLimit();

        return $this->db->fetchFirstColumn($sql, []);
    }

    public function getDataArray(): array
    {
        $configsData = $this->db->fetchAllAssociative('SELECT * FROM ' . 'teams' . $this->getCondition() . $this->getOrder() . $this->getOffsetLimit(), []);

        return $configsData;
    }

    public function getTotalCount(): int
    {
        try {
            return (int) $this->db->fetchOne('SELECT COUNT(*) FROM ' . 'teams ' . $this->getCondition(), []);
        } catch (\Exception $e) {
            return 0;
        }
    }
}
