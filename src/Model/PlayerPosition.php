<?php

namespace App\Model;

use App\Repository\PlayerPositionRepository;
use Doctrine\ORM\Mapping as ORM;
use Pimcore\Model\AbstractModel;
use Pimcore\Model\Exception\NotFoundException;

#[ORM\Entity(repositoryClass: PlayerPositionRepository::class)]
class PlayerPosition extends AbstractModel
{
    public ?int $id = null;
    public ?string $name = null;

    public static function getById(int $id): ?PlayerPosition
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

    public static function getByName(string $name): ?PlayerPosition
    {
        try {
            $obj = new self;
            $obj->getDao()->getByClause(['name' => $name]);
            return $obj;
        } catch (NotFoundException) {
            \Pimcore\Logger::warn(sprintf('PlayerPosition with name %s not found', $name));
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }
}
