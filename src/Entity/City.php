<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CityRepository")
 */
class City
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nameCity;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Airport", mappedBy="city")
     */
    private $airports;

    public function __construct()
    {
        $this->airports = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameCity(): ?string
    {
        return $this->nameCity;
    }

    public function setNameCity(string $nameCity): self
    {
        $this->nameCity = $nameCity;

        return $this;
    }

    /**
     * @return Collection|Airport[]
     */
    public function getAirports(): Collection
    {
        return $this->airports;
    }

    public function addAirport(Airport $airport): self
    {
        if (!$this->airports->contains($airport)) {
            $this->airports[] = $airport;
            $airport->setCity($this);
        }

        return $this;
    }

    public function removeAirport(Airport $airport): self
    {
        if ($this->airports->contains($airport)) {
            $this->airports->removeElement($airport);
            // set the owning side to null (unless already changed)
            if ($airport->getCity() === $this) {
                $airport->setCity(null);
            }
        }

        return $this;
    }
}
