<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BookingRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Booking
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="bookings")
     */
    private $booker;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Seat", inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $seat;

    /**
     * @ORM\Column(type="string", nullable=true, length=255)
     */
    private $numBooking;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Flight", inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $flight;

    /**
     * @ORM\Column(type="boolean")
     */
    private $activeSeat;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $bookingCreatedAt;

    // /**
    //  * Callback appelé à chaque fois qu'on créé une reservation
    //  * 
    //  * @ORM\PrePersist
    //  *
    //  * @return void
    //  */
    // public function prePersist(){
    //     if(empty($this->bookingCreatedAt)){
    //         $this->bookingCreatedAt = new \DateTime();
    //     }
    // }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBooker(): ?User
    {
        return $this->booker;
    }

    public function setBooker(?User $booker): self
    {
        $this->booker = $booker;

        return $this;
    }

    public function getSeat(): ?Seat
    {
        return $this->seat;
    }

    public function setSeat(?Seat $seat): self
    {
        $this->seat = $seat;

        return $this;
    }

    public function getNumBooking(): ?string
    {
        return $this->numBooking;
    }

    public function setNumBooking(string $numBooking): self
    {
        $this->numBooking = $numBooking;

        return $this;
    }

    public function getFlight(): ?Flight
    {
        return $this->flight;
    }

    public function setFlight(?Flight $flight): self
    {
        $this->flight = $flight;

        return $this;
    }

    public function getActiveSeat(): ?bool
    {
        return $this->activeSeat;
    }

    public function setActiveSeat(bool $activeSeat): self
    {
        $this->activeSeat = $activeSeat;

        return $this;
    }

    public function getBookingCreatedAt(): ?\DateTimeInterface
    {
        return $this->bookingCreatedAt;
    }

    public function setBookingCreatedAt(?\DateTimeInterface $bookingCreatedAt): self
    {
        $this->bookingCreatedAt = $bookingCreatedAt;

        return $this;
    }
}
