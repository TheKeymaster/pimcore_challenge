<?php

namespace App\Model;

use App\Repository\TrainerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Pimcore\Model\AbstractModel;
use Pimcore\Model\Exception\NotFoundException;

#[ORM\Entity(repositoryClass: TrainerRepository::class)]
class Trainer extends AbstractModel
{
    public ?int $id = null;
    public ?string $first_name = null;
    public ?string $last_name = null;

    /**
     * @var Collection<int, Team>
     */
    #[ORM\OneToMany(mappedBy: 'trainer', targetEntity: Team::class)]
    private Collection $teams;

    public static function getById(int $id): ?Trainer
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

    public static function getByName(string $name): ?Trainer
    {
        $names = explode(' ', $name);
        $firstName = $names[0] ?? null;
        $lastName = $names[1] ?? null;

        try {
            $obj = new self;
            $obj->getDao()->getByClause([
                'first_name' => $firstName,
                'last_name' => $lastName
            ]);
            return $obj;
        } catch (NotFoundException) {
            \Pimcore\Logger::warn(sprintf('Trainer with name %s not found', $name));
        }

        return null;
    }

    public function __construct()
    {
        $this->teams = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
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

    /**
     * @return Collection<int, Team>
     */
    public function getTeams(): Collection
    {
        return $this->teams;
    }

    public function addTeam(Team $team): static
    {
        if (!$this->teams->contains($team)) {
            $this->teams->add($team);
            $team->setTrainerId($this);
        }

        return $this;
    }

    public function removeTeam(Team $team): static
    {
        if ($this->teams->removeElement($team)) {
            // set the owning side to null (unless already changed)
            if ($team->getTrainerId() === $this) {
                $team->setTrainerId(null);
            }
        }

        return $this;
    }
}
