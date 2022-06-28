<?php

namespace App\Entity;

use App\Repository\StationsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StationsRepository::class)]
class Station
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 30)]
    #[ORM\OneToMany(targetEntity:'Plug',mappedBy:'station')]
    private $location;

    #[ORM\Column(type: 'string', length: 50)]
    private $name;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
