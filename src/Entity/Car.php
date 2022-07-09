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
    #[ORM\OneToMany(targetEntity:'UsersCarsREDUNDANT',mappedBy:'car')]
    private string $plate;

    #[ORM\Column(type: 'string', length: 10)]
    private string $plug_type;

    //#[ORM\OneToMany(mappedBy: 'user', targetEntity: Users::class, orphanRemoval: true, indexBy: 'id')]
    #[ORM\ManyToMany(targetEntity: Users::class,mappedBy: 'cars')]
    private mixed $users;

    #[ORM\OneToMany(mappedBy: 'car', targetEntity: Booking::class, orphanRemoval: true, indexBy: 'id')]
//    #[ORM\OneToOne(inversedBy: 'car', targetEntity: Booking::class, cascade: ['persist', 'remove'])]
    private $bookings;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $capacity;
    /**
     * @return mixed
     */
    public function getUsers()
    {
        return $this->users;
    }

    public function addUser(Users $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }
        return $this;
    }

    /**
     * @param mixed $users
     */
    public function setUsers($users): void
    {
        $this->users = $users;
    }

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

    public function getBookings(): mixed
    {
        return $this->bookings;
    }

    public function setBookings($bookings): self
    {
        $this->bookings = $bookings;

        return $this;
    }

    public function getCapacity(): ?int
    {
        return $this->capacity;
    }

    public function setCapacity(?int $capacity): self
    {
        $this->capacity = $capacity;

        return $this;
    }
}
