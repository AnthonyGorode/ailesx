<?php

namespace App\Service;

use PDO;
use Doctrine\Common\Persistence\ObjectManager;


class StatsAilesx{
    private $manager;

    public function __construct(ObjectManager $manager){
        $this->manager = $manager;
    }

    public function setGlobalVariableOnlyGroupByMySql(){
        $pdo  = new PDO('mysql:host=localhost;charset=utf8', 'root', '');
        $query = 'SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,"ONLY_FULL_GROUP_BY",""))';

        $resultDb = $pdo->prepare($query);
        $resultDb->execute();

        $pdo = null;
    }

    public function getStats(){
        $users = $this->getUsersCount();
        $flights = $this->getFlightsCount();
        $bookings = $this->getBookingsCount();
        $airports = $this->getDestinationsCount();

        dump(compact('users','flights', 'bookings', 'airports'));

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
        $countUser = $this->manager->createQuery('SELECT COUNT(u) FROM App\Entity\User u')->getSingleScalarResult();
        
        return $countUser;
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