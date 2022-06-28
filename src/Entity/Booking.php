<?php

namespace App\Entity;

use App\Repository\BookingRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookingRepository::class)]
class Booking
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'datetime')]
    private $start_time;

    #[ORM\Column(type: 'integer')]
    private $duration;

    #[ORM\ManyToOne(targetEntity: Car::class)]
    #[ORM\JoinColumn(referencedColumnName: 'plate',nullable: false)]
    private $car;

    #[ORM\ManyToOne(targetEntity: Plug::class)]
    #[ORM\JoinColumn(referencedColumnName: 'id',nullable: false)]
    private $plug;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->start_time;
    }

    public function setStartTime(\DateTimeInterface $start_time): self
    {
        $this->start_time = $start_time;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getCar(): ?Car
    {
        return $this->car;
    }

    public function setCar(?Car $car): self
    {
        $this->car = $car;

        return $this;
    }

    public function getPlug(): ?Plug
    {
        return $this->plug;
    }

    public function setPlug(?Plug $plug): self
    {
        $this->plug = $plug;

        return $this;
    }
}