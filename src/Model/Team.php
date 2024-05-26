<?php

namespace App\Model;

use App\Repository\TeamRepository;
use Doctrine\ORM\Mapping as ORM;
use Pimcore\Model\Exception\NotFoundException;

#[ORM\Entity(repositoryClass: TeamRepository::class)]
class Team extends AbstractBaseModel
{
    public ?int $id = null;
    public ?string $name = null;
    public ?string $logo = null;
    public ?int $founded_at = null;
    public ?int $trainer_id = null;
    public ?int $location_id = null;

    private ?int $player_count = null;

    private ?array $players = null;

    private ?array $trainer = null;

    private ?array $location = null;

    public static function getById(?int $id, ?Team $default = null): ?Team
    {
        if (!$id) {
            return $default;
        }

        try {
            $obj = new self;
            $obj->getDao()->getById($id);
            return $obj;
        }
        catch (NotFoundException) {
            \Pimcore\Logger::warn(sprintf('Team with id %d not found', $id));
        }

        return $default;
    }

    public static function getByName(string $name): ?Team
    {
        try {
            $obj = new self;
            $obj->getDao()->getByClause(['name' => $name]);
            return $obj;
        } catch (NotFoundException) {
            \Pimcore\Logger::warn(sprintf('Team with name %s not found', $name));
        }

        return null;
    }

    public static function getByIdWithPlayers(?int $id, ?Team $default = null): ?Team
    {
        if (!$id) {
            return $default;
        }

        try {
            $obj = new self;
            $obj->getDao()->getByIdWithPlayersAndTrainer($id);
            return $obj;
        }
        catch (NotFoundException) {
            \Pimcore\Logger::warn(sprintf('Team with id %d not found', $id));
        }

        return $default;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(string $logo): static
    {
        $this->logo = $logo;

        return $this;
    }

    public function getFoundedAt(): ?int
    {
        return $this->founded_at;
    }

    public function setFoundedAt(int $founded_at): static
    {
        $this->founded_at = $founded_at;

        return $this;
    }

    public function getTrainerId(): ?int
    {
        return $this->trainer_id;
    }

    public function setTrainerId(?int $trainer_id): static
    {
        $this->trainer_id = $trainer_id;

        return $this;
    }

    public function getLocationId(): ?int
    {
        return $this->location_id;
    }

    public function setLocationId(?int $location_id): static
    {
        $this->location_id = $location_id;

        return $this;
    }

    public function getLocation(): ?array
    {
        return $this->location;
    }

    public function setLocation(array $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getPlayerCount(): ?int
    {
        return $this->player_count;
    }

    public function setPlayerCount(?int $player_count): static
    {
        $this->player_count = $player_count;

        return $this;
    }

    public function getPlayers(): ?array
    {
        return $this->players;
    }

    public function setPlayers(?array $players): static
    {
        $this->players = $players;

        return $this;
    }

    public function getTrainer(): ?array
    {
        return $this->trainer;
    }

    public function setTrainer(?array $trainer): static
    {
        $this->trainer = $trainer;

        return $this;
    }
}
