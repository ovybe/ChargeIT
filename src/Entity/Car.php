<?php

namespace App\Entity;

use App\Repository\CarRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CarRepository::class)]
class Car
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 10)]
    #[ORM\OneToMany(targetEntity:'UsersCars',mappedBy:'car')]
    private $plate;

    #[ORM\Column(type: 'string', length: 10)]
    private $plug_type;

    public function __construct()
    {
        $this->user_id = new ArrayCollection();
    }

    public function getPlate(): ?string
    {
        return $this->plate;
    }

    public function setPlate(string $plate): self
    {
        $this->plate = $plate;

        return $this;
    }

    public function getPlugType(): ?string
    {
        return $this->plug_type;
    }

    public function setPlugType(string $plug_type): self
    {
        $this->plug_type = $plug_type;

        return $this;
    }

    public function getPlug_Type(): ?string
    {
        return $this->plug_type;
    }

    public function setPlug_Type(string $plug_type): self
    {
        $this->plug_type = $plug_type;

        return $this;
    }

    /**
     * @return Collection<int, Users>
     */
    public function getUserId(): Collection
    {
        return $this->user_id;
    }

    public function addUserId(Users $userId): self
    {
        if (!$this->user_id->contains($userId)) {
            $this->user_id[] = $userId;
        }

        return $this;
    }

    public function removeUserId(Users $userId): self
    {
        $this->user_id->removeElement($userId);

        return $this;
    }
}
