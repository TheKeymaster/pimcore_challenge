<?php

namespace App\Model;

use App\Repository\LocationRepository;
use Doctrine\ORM\Mapping as ORM;
use Pimcore\Model\AbstractModel;
use Pimcore\Model\Exception\NotFoundException;

#[ORM\Entity(repositoryClass: LocationRepository::class)]
class Location extends AbstractModel
{
    public ?int $id = null;
    public ?string $name = null;
    public ?float $lat = null;
    public ?float $lon = null;

    public static function getById(?int $id, ?Location $default = null): ?Location
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
            \Pimcore\Logger::warn(sprintf('Location with id %d not found', $id));
        }

        return $default;
    }

    public static function getByName(string $name): ?Location
    {
        try {
            $obj = new self;
            $obj->getDao()->getByClause(['name' => $name]);
            return $obj;
        } catch (NotFoundException) {
            \Pimcore\Logger::warn(sprintf('Location with name %s not found', $name));
        }

        return null;
    }

    public function setData(array $data): void
    {
        if ($data['id'] !== null) {
            $this->setId($data['id']);
        }

        if ($data['name'] !== null) {
            $this->setName($data['name']);
        }

        if ($data['lat'] !== null) {
            $this->setLat($data['lat']);
        }

        if ($data['lon'] !== null) {
            $this->setLon($data['lon']);
        }
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

    public function getLat(): ?float
    {
        return $this->lat;
    }

    public function setLat(float $lat): static
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLon(): ?float
    {
        return $this->lon;
    }

    public function setLon(float $lon): static
    {
        $this->lon = $lon;

        return $this;
    }
}
