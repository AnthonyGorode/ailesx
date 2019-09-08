<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FlightRepository")
 */
class Flight
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
    private $numFlight;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Airport", inversedBy="flights")
     * @ORM\JoinColumn(nullable=false)
     */
    private $airportDeparture;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Airport", inversedBy="flightsArrival")
     * @ORM\JoinColumn(nullable=false)
     */
    private $airportArrival;

    /**
     * @ORM\Column(type="datetime")
     */
    private $hourDeparture;

    /**
     * @ORM\Column(type="datetime")
     */
    private $hourArrival;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Booking", mappedBy="flight")
     */
    private $bookings;

    public function __construct()
    {
        $this->flightSeats = new ArrayCollection();
        $this->bookings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumFlight(): ?string
    {
        return $this->numFlight;
    }

    public function setNumFlight(string $numFlight): self
    {
        $this->numFlight = $numFlight;

        return $this;
    }

    public function getAirportDeparture(): ?Airport
    {
        return $this->airportDeparture;
    }

    public function setAirportDeparture(?Airport $airportDeparture): self
    {
        $this->airportDeparture = $airportDeparture;

        return $this;
    }

    public function getAirportArrival(): ?Airport
    {
        return $this->airportArrival;
    }

    public function setAirportArrival(?Airport $airportArrival): self
    {
        $this->airportArrival = $airportArrival;

        return $this;
    }

    public function getHourDeparture(): ?\DateTimeInterface
    {
        return $this->hourDeparture;
    }

    public function setHourDeparture(\DateTimeInterface $hourDeparture): self
    {
        $this->hourDeparture = $hourDeparture;

        return $this;
    }

    public function getHourArrival(): ?\DateTimeInterface
    {
        return $this->hourArrival;
    }

    public function setHourArrival(\DateTimeInterface $hourArrival): self
    {
        $this->hourArrival = $hourArrival;

        return $this;
    }

    /**
     * @return Collection|Booking[]
     */
    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    public function addBooking(Booking $booking): self
    {
        if (!$this->bookings->contains($booking)) {
            $this->bookings[] = $booking;
            $booking->setFlight($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): self
    {
        if ($this->bookings->contains($booking)) {
            $this->bookings->removeElement($booking);
            // set the owning side to null (unless already changed)
            if ($booking->getFlight() === $this) {
                $booking->setFlight(null);
            }
        }

        return $this;
    }

}
