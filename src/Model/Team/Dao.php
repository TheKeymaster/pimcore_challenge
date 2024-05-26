<?php

namespace App\Model\Team;

use App\Model\AbstractBaseDao;
use Doctrine\DBAL\Exception;
use Pimcore\Model\Exception\NotFoundException;

class Dao extends AbstractBaseDao
{
    /**
     * @throws NotFoundException
     * @throws Exception
     */
    public function getById(int $id): void
    {
        $sql = <<<SQL
SELECT t.*, COUNT(p.id) as player_count
FROM {$this->getTableName()} t
LEFT JOIN players p ON t.id = p.team_id
WHERE t.id = ?
GROUP BY t.id
SQL;
        $data = $this->db->fetchAssociative($sql, [$id]);

        if (!$data) {
            throw new NotFoundException(sprintf('Unable to load site with ID `%s`', $id));
        }

        $this->assignVariablesToModel($data);
    }

    public function getByIdWithPlayersAndTrainer(int $id): void
    {
        $sqlTeam = <<<SQL
SELECT t.*
FROM {$this->getTableName()} t
WHERE t.id = ?
SQL;
        $data = $this->db->fetchAssociative($sqlTeam, [$id]);
        $trainerId = $data['trainer_id'];
        $locationId = $data['location_id'];

        $sqlPlayers = <<<SQL
SELECT p.*, ps.*
FROM players p
LEFT JOIN player_positions ps ON ps.id = p.position_id
WHERE p.team_id = ?
SQL;
        $data['players'] = $this->db->fetchAllAssociative($sqlPlayers, [$id]);

        $sqlTrainers = <<<SQL
SELECT tr.*
FROM trainers tr
WHERE tr.id = ?
SQL;

        $data['trainer'] = $this->db->fetchAssociative($sqlTrainers, [$trainerId]);

        $sqlLocations = <<<SQL
SELECT l.*
FROM locations l
WHERE l.id = ?
SQL;

        $data['location'] = $this->db->fetchAssociative($sqlLocations, [$locationId]);

        if (!$data) {
            throw new NotFoundException(sprintf('Unable to load site with ID `%s`', $id));
        }

        $this->assignVariablesToModel($data);
    }

    public function getTableName(): string
    {
        return 'teams';
    }
}
