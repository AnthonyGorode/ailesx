<?php

namespace App\Service;

use Doctrine\Common\Persistence\ObjectManager;


class StatsAilesx{
    private $manager;

    public function __construct(ObjectManager $manager){
        $this->manager = $manager;
    }

    public function getStats(){
        $users = $this->getUsersCount();
        $flights = $this->getFlightsCount();
        $bookings = $this->getBookingsCount();
        $airports = $this->getDestinationsCount();

        return compact('users','flights', 'bookings', 'airports');
    }

    public function getDestinationStats($direction){
        return $this->manager->createQuery(
            'SELECT COUNT(b.booker) as totalBooker,c.nameCity,f.id as idflight
             FROM App\Entity\Booking b
             LEFT JOIN b.flight f
             LEFT JOIN f.airportArrival a
             LEFT JOIN a.city c
             GROUP BY c
             ORDER BY totalBooker '. $direction
            
        )
        ->setMaxResults(5)
        ->getResult();
    }

    public function getUsersCount(){
        return $this->manager->createQuery('SELECT COUNT(u) FROM App\Entity\User u')->getSingleScalarResult();
    }

    public function getFlightsCount(){
        return $this->manager->createQuery('SELECT COUNT(f) FROM App\Entity\Flight f')->getSingleScalarResult();
    }

    public function getBookingsCount(){
        return $this->manager->createQuery('SELECT COUNT(b.booker) FROM App\Entity\booking b')->getSingleScalarResult();
    }

    public function getDestinationsCount(){
        return $this->manager->createQuery('SELECT COUNT(a) FROM App\Entity\Airport a')->getSingleScalarResult();
    }
}

?>