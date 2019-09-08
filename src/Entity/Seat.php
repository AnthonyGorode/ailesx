<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SeatRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Seat
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
    private $numSeat;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Jet", inversedBy="seats")
     * @ORM\JoinColumn(nullable=false)
     */
    private $jet;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Booking", mappedBy="seat")
     */
    private $bookings;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

     /**
     * Permet d'initialiser le slug
     * 
     * @ORM\PrePersist
     * @ORM\PreUpdate
     * 
     */
    public function initializeSlug(){
        $slugify = new Slugify();
        if(empty($this->slug)){
            $this->slug = $slugify->slugify($this->numSeat);
        }
    }

    public function __construct()
    {
        $this->bookings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumSeat(): ?string
    {
        return $this->numSeat;
    }

    public function setNumSeat(string $numSeat): self
    {
        $this->numSeat = $numSeat;

        return $this;
    }

    public function getJet(): ?Jet
    {
        return $this->jet;
    }

    public function setJet(?Jet $jet): self
    {
        $this->jet = $jet;

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
            $booking->setSeat($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): self
    {
        if ($this->bookings->contains($booking)) {
            $this->bookings->removeElement($booking);
            // set the owning side to null (unless already changed)
            if ($booking->getSeat() === $this) {
                $booking->setSeat(null);
            }
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

}
