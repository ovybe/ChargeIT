<?php

namespace App\Entity;

use App\Repository\PricesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PricesRepository::class)]
class Prices
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $amount;

    #[ORM\OneToOne(targetEntity: plug::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private $plug;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(?int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getPlug(): ?plug
    {
        return $this->plug;
    }

    public function setPlug(plug $plug): self
    {
        $this->plug = $plug;

        return $this;
    }
}
