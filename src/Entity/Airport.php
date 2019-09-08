<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AirportRepository")
 */
class Airport
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
    private $nameAirport;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\City", inversedBy="airports")
     * @ORM\JoinColumn(nullable=false)
     */
    private $city;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Flight", mappedBy="airportDeparture")
     */
    private $flights;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Flight", mappedBy="airportArrival")
     */
    private $flightsArrival;

    public function __construct()
    {
        $this->flights = new ArrayCollection();
        $this->flightsArrival = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameAirport(): ?string
    {
        return $this->nameAirport;
    }

    public function setNameAirport(string $nameAirport): self
    {
        $this->nameAirport = $nameAirport;

        return $this;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return Collection|Flight[]
     */
    public function getFlights(): Collection
    {
        return $this->flights;
    }

    public function addFlight(Flight $flight): self
    {
        if (!$this->flights->contains($flight)) {
            $this->flights[] = $flight;
            $flight->setAirportDeparture($this);
        }

        return $this;
    }

    public function removeFlight(Flight $flight): self
    {
        if ($this->flights->contains($flight)) {
            $this->flights->removeElement($flight);
            // set the owning side to null (unless already changed)
            if ($flight->getAirportDeparture() === $this) {
                $flight->setAirportDeparture(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Flight[]
     */
    public function getFlightsArrival(): Collection
    {
        return $this->flightsArrival;
    }

    public function addFlightsArrival(Flight $flightsArrival): self
    {
        if (!$this->flightsArrival->contains($flightsArrival)) {
            $this->flightsArrival[] = $flightsArrival;
            $flightsArrival->setAirportArrival($this);
        }

        return $this;
    }

    public function removeFlightsArrival(Flight $flightsArrival): self
    {
        if ($this->flightsArrival->contains($flightsArrival)) {
            $this->flightsArrival->removeElement($flightsArrival);
            // set the owning side to null (unless already changed)
            if ($flightsArrival->getAirportArrival() === $this) {
                $flightsArrival->setAirportArrival(null);
            }
        }

        return $this;
    }

}
