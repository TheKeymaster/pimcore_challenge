<?php

namespace App\Model;

use App\Repository\PlayerRepository;
use Doctrine\ORM\Mapping as ORM;
use Pimcore\Model\AbstractModel;
use Pimcore\Model\Exception\NotFoundException;

#[ORM\Entity(repositoryClass: PlayerRepository::class)]
class Player extends AbstractModel
{
    public ?int $id = null;
    public ?string $first_name = null;
    public ?string $last_name = null;
    public ?int $field_number = null;
    public ?int $age = null;
    public ?int $position_id = null;
    public ?int $team_id = null;

    public static function getById(int $id): ?Player
    {
        try {
            $obj = new self;
            $obj->getDao()->getById($id);
            return $obj;
        }
        catch (NotFoundException) {
            \Pimcore\Logger::warn(sprintf('Trainer with id %d not found', $id));
        }

        return null;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): static
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): static
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getFieldNumber(): ?int
    {
        return $this->field_number;
    }

    public function setFieldNumber(int $field_number): static
    {
        $this->field_number = $field_number;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): static
    {
        $this->age = $age;

        return $this;
    }

    public function getPositionId(): ?int
    {
        return $this->position_id;
    }

    public function setPositionId(?int $position_id): static
    {
        $this->position_id = $position_id;

        return $this;
    }

    public function getTeamId(): ?int
    {
        return $this->team_id;
    }

    public function setTeamId(?int $team_id): static
    {
        $this->team_id = $team_id;

        return $this;
    }
}
