<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 100)]
    #[ORM\OneToMany(targetEntity: 'UsersCars',mappedBy: 'user')]
    private $email;

    #[ORM\Column(type: 'string', length: 50)]
    private $name;

    #[ORM\Column(type: 'string', length: 64)]
    private $auth;

    public function __construct()
    {
        $this->car_id = new ArrayCollection();
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

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

    public function getAuth(): ?string
    {
        return $this->auth;
    }

    public function setAuth(string $auth): self
    {
        $this->auth = $auth;

        return $this;
    }

    /**
     * @return Collection<int, Car>
     */
    public function getCarId(): Collection
    {
        return $this->car_id;
    }

    public function addCarId(Car $carId): self
    {
        if (!$this->car_id->contains($carId)) {
            $this->car_id[] = $carId;
            $carId->addUserId($this);
        }

        return $this;
    }

    public function removeCarId(Car $carId): self
    {
        if ($this->car_id->removeElement($carId)) {
            $carId->removeUserId($this);
        }

        return $this;
    }
}
