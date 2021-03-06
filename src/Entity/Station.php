<?php

namespace App\Entity;

use App\Repository\StationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: StationRepository::class)]
class Station
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type:'guid',unique:true)]
    private $uuid;

    #[ORM\Column(type: 'string', length: 30)]
    private $location;

    #[ORM\Column(type:'decimal',  precision:18, scale:15)]
    private $latitude;
    #[ORM\Column(type:'decimal',  precision:18, scale:15)]
    private $longitude;

    #[ORM\Column(type: 'string', length: 50)]
    private $name;

    #[ORM\OneToMany(mappedBy: 'station', targetEntity: Plug::class, orphanRemoval: true)]
    private $plugs;

    public function __construct()
    {
        $this->plugs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }
    public function genUuid(): self
    {
            $this->uuid = Uuid::v4();
            return $this;
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


    public function getLatitude(): ?string
    {
        return $this->latitude;
    }


    public function setLatitude($latitude): self
    {
        $this->latitude = $latitude;
        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }


    public function setLongitude($longitude): self
    {
        $this->longitude = $longitude;
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

    /**
     * @return Collection<int, Plug>
     */
    public function getPlugs(): Collection
    {
        return $this->plugs;
    }

    public function addPlug(Plug $plug): self
    {
        if (!$this->plugs->contains($plug)) {
            $this->plugs[] = $plug;
            $plug->setStation($this);
        }

        return $this;
    }

    public function removePlug(Plug $plug): self
    {
        if ($this->plugs->removeElement($plug)) {
            // set the owning side to null (unless already changed)
            if ($plug->getStation() === $this) {
                $plug->setStation(null);
            }
        }

        return $this;
    }
}
