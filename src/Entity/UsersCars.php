<?php

namespace App\Entity;

use App\Repository\UsersCarsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UsersCarsRepository::class)]
class UsersCars
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Users::class)]
    #[ORM\JoinColumn(referencedColumnName: 'id',nullable: false)]
    private $user;
    #[ORM\ManyToOne(targetEntity: Car::class)]
    #[ORM\JoinColumn(referencedColumnName: 'plate',nullable: false)]
    private $car;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?Users
    {
        return $this->user;
    }

    public function setUserId(?Users $user_id): self
    {
        $this->user = $user_id;

        return $this;
    }

    public function getCarId(): ?Car
    {
        return $this->car;
    }

    public function setCarId(?Car $car_id): self
    {
        $this->car = $car_id;

        return $this;
    }
}
