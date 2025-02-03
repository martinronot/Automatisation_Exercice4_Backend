<?php

namespace App\Entity;

use App\Repository\BuildingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BuildingRepository::class)]
class Building
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[ORM\Column]
    private ?int $capacity = null;

    #[ORM\ManyToMany(targetEntity: Person::class)]
    private Collection $occupants;

    public function __construct()
    {
        $this->occupants = new ArrayCollection();
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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;
        return $this;
    }

    public function getCapacity(): ?int
    {
        return $this->capacity;
    }

    public function setCapacity(int $capacity): static
    {
        $this->capacity = $capacity;
        return $this;
    }

    /**
     * @return Collection<int, Person>
     */
    public function getOccupants(): Collection
    {
        return $this->occupants;
    }

    public function addOccupant(Person $occupant): static
    {
        if (!$this->occupants->contains($occupant)) {
            $this->occupants->add($occupant);
        }

        return $this;
    }

    public function removeOccupant(Person $occupant): static
    {
        $this->occupants->removeElement($occupant);
        return $this;
    }
}
