<?php

namespace App\Repository;

use App\Entity\Flight;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Flight|null find($id, $lockMode = null, $lockVersion = null)
 * @method Flight|null findOneBy(array $criteria, array $orderBy = null)
 * @method Flight[]    findAll()
 * @method Flight[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FlightRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Flight::class);
    }

    
    /**
     * Permet de mettre à jour les destinations d'un vol
     * 
     * @return Flight[] Returns an array of Flight objects
     */
    public function updateAirportFlight($idDeparture,$idArrival,$idFlight)
    {
        $qb = $this->createQueryBuilder('f');
        $q = $qb->update('App\Entity\Flight', 'f')
                ->set('f.airportDeparture', $qb->expr()->literal($idDeparture))
                ->set('f.airportArrival', $qb->expr()->literal($idArrival))
                ->where($qb->expr()->eq('f.id', $idFlight))
                ->getQuery();
       return $p = $q->execute();
    }

    /**
     * Cette fonction sert à l'administration
     * Permet de créer un tableau des vols et d'afficher les réservations les concernants
     *
     * @param [type] $column
     * @param [type] $order
     * @param [type] $limit
     * @param [type] $offset
     * @return void
     */
    public function adminArray($column,$order,$limit,$offset,$concat){
        // si il n'y a pas de recherche affiche tous la table
        // sinon affiche seulement le résultat de la recherche
        if(empty($concat)){
            return $this->createQueryBuilder('f')
                        ->select(
                            'count(fb.booker) as countBooker',
                            'count(fb.seat) as countSeat',
                            'f.numFlight',
                            'f.id',
                            'fd.id as idAirportDeparture',
                            'fd.nameAirport as airportDeparture',
                            'fa.id as idAirportArrival',
                            'fa.nameAirport as airportArrival',
                            'f.hourDeparture',
                            'f.hourArrival'
                        )
                        ->leftJoin('f.bookings','fb')
                        ->leftJoin('f.airportDeparture','fd')
                        ->leftJoin('f.airportArrival','fa')
                        ->groupBy('fb.flight')
                        ->orderBy($column,$order)
                        ->setMaxResults($limit)
                        ->setFirstResult($offset)
                        ->getQuery()
                        ->getResult()
                    ;
        }else{
            return $this->createQueryBuilder('f')
                        ->select('count(fb.booker) as countBooker','count(fb.seat) as countSeat','f.numFlight','f.id','fd.id as idAirportDeparture','fd.nameAirport as airportDeparture','fa.id as idAirportArrival','fa.nameAirport as airportArrival','f.hourDeparture','f.hourArrival')
                        ->leftJoin('f.bookings','fb')
                        ->leftJoin('f.airportDeparture','fd')
                        ->leftJoin('f.airportArrival','fa')
                        ->where('concat(fd.nameAirport,fa.nameAirport) like :concat')
                        ->setParameter('concat','%'.$concat.'%')
                        ->groupBy('fb.flight')
                        ->orderBy($column,$order)
                        ->setMaxResults($limit)
                        ->setFirstResult($offset)
                        ->getQuery()
                        ->getResult()
                    ;
        }
    }

    /**
     * Cette fonction sert à l'administration
     * Permet de créer un tableau des vols et d'afficher les réservations les concernants
     *
     * @return void
     */
    public function flightToday(){
        $date = new \DateTime();
        $from = new \DateTime($date->format("Y-m-d H:i:s"));
        $to   = new \DateTime($date->format("Y-m-d")." 23:59:59");

        
                return $this->createQueryBuilder('f')
                    ->select('f.id','fdc.nameCity as cityDeparture','fac.nameCity as cityArrival','f.hourDeparture')
                    ->leftJoin('f.bookings','fb')
                    ->leftJoin('f.airportDeparture','fd')
                    ->leftJoin('fd.city','fdc')
                    ->leftJoin('f.airportArrival','fa')
                    ->leftJoin('fa.city','fac')
                    ->where('f.hourDeparture BETWEEN :from AND :to')
                    ->setParameter('from', $from)
                    ->setParameter('to', $to)
                    ->groupBy('fb.flight')
                    ->setMaxResults(5)
                    ->getQuery()
                    ->getResult()
                ;

        
    }
    

    // /**
    //  * @return Flight[] Returns an array of Flight objects
    //  */
    /*
    public function findSearchFlights($value)
    {
        return $this->createQueryBuilder('f')
            ->leftJoin('f.airportDeparture', 'd')
            ->leftJoin('f.airportArrival', 'a')
            ->leftJoin('d.city', 'dc')
            ->leftJoin('a.city', 'ac')
            ->andWhere('dc.nameCity = :departure')
            ->andWhere('ac.nameCity = :arrival')
            ->andWhere('f.hourDeparture = :departure')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Flight
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
