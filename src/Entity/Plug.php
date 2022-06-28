<?php

namespace App\Entity;

use App\Repository\PlugRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlugRepository::class)]
class Plug
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'boolean')]
    private $status;

    #[ORM\ManyToOne(targetEntity: Station::class)]
    #[ORM\JoinColumn(referencedColumnName: 'location',nullable: false)]
    private $station;

    #[ORM\Column(type: 'string', length: 10)]
    private $type;

    #[ORM\Column(type: 'decimal', precision: 5, scale: 1)]
    private $max_output;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getStation(): ?string
    {
        return $this->station->getLocation();
    }

    public function setStation(string $station_id): self
    {
        $this->station = $station_id;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getMax_Output(): ?string
    {
        return $this->max_output;
    }

    public function setMax_Output(string $max_output): self
    {
        $this->max_output = $max_output;

        return $this;
    }
}
