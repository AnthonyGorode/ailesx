<?php

namespace App\Repository;

use DateTime;
use App\Entity\Booking;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Booking|null find($id, $lockMode = null, $lockVersion = null)
 * @method Booking|null findOneBy(array $criteria, array $orderBy = null)
 * @method Booking[]    findAll()
 * @method Booking[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookingRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Booking::class);
    }

    /**
     * Permet de récupérer tous les sièges disponibles pour un vol
     * 
     * @return Booking[] Returns an array of Booking objects
     */
    public function findAllSeatsForOneFlight($flight,$activeSeat)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.flight = :flight')
            ->andWhere('b.activeSeat = :activeSeat')
            ->setParameter('flight', $flight)
            ->setParameter('activeSeat', $activeSeat)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Permet de récupérer la réservation choisi par l'utilisateur
     * 
     * @return Booking[] Returns an array of Booking objects
     */
    public function findOneBooking($flight,$seat)
    {
        return $this->createQueryBuilder('b')
            ->select('b.id')
            ->andWhere('b.flight = :flight')
            ->andWhere('b.seat = :seat')
            ->setParameter('flight', $flight)
            ->setParameter('seat', $seat)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Permet de récupérer tous les réservations d'un utilisateur
     * 
     * @return Booking[] Returns an array of Booking objects
     */
    public function findAllBookingUser($flight,$seat)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.flight = :flight')
            ->andWhere('b.seat = :seat')
            ->setParameter('flight', $flight)
            ->setParameter('seat', $seat)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Permet de modifier l'entité booking 
     * pour insérer la réservation de l'utilisateur
     *
     * @param [type] $bookingId
     * @return void
     */
    public function updateBooking($bookingId,$user,$dateToday)
    {
        $qb = $this->createQueryBuilder('b');
        $q = $qb->update('App\Entity\Booking', 'b')
                ->set('b.booker', $qb->expr()->literal($user))
                ->set('b.bookingCreatedAt', $qb->expr()->literal($dateToday))
                ->set('b.activeSeat', $qb->expr()->literal(1))
                ->where($qb->expr()->eq('b.id', $bookingId))
                ->getQuery();
       return $p = $q->execute();  
    }
 
    /**
     * Cette fonction sert à l'administration
     * Permet de compter le nombre de utilisateur ayant réserver pour chaque vol
     *
     * @return void
     */
    public function adminArray($column,$order,$limit,$offset,$concat){
        // $dateObject = new \DateTime();
        // $dateToday = $dateObject->format('Y-m-d H:i:s');
        // dump($dateToday);
        if(empty($concat)){

            return $this->createQueryBuilder('b')
                        ->select('b.id','b.numBooking','b.bookingCreatedAt','bf.numFlight','bu.lastName', 'bu.firstName', 'bu.picture','bs.numSeat')
                        ->leftJoin('b.flight', 'bf')
                        ->leftJoin('b.booker', 'bu')
                        ->leftJoin('b.seat','bs')
                        ->orderBy($column,$order)
                        ->setMaxResults($limit)
                        ->setFirstResult($offset)
                        ->getQuery()
                        ->getResult()
                    ;
        }else{
            return $this->createQueryBuilder('b')
                    ->select('b.id','b.numBooking','b.bookingCreatedAt','bf.numFlight','bu.lastName', 'bu.firstName', 'bu.picture','bs.numSeat')
                    ->leftJoin('b.flight', 'bf')
                    ->leftJoin('b.booker', 'bu')
                    ->leftJoin('b.seat','bs')
                    ->where('concat(bf.numFlight,b.numBooking,bu.firstName,bu.lastName) like :concat')
                    ->setParameter('concat','%'.$concat.'%')
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
     * Permet d'afficher le total d'utilisateurs en fonction d'une recherche
     *
     * @param [type] $column
     * @param [type] $order
     * @param [type] $offset
     * @param [type] $concat
     * @return void
     */
    public function countArray($column,$order,$offset,$concat){
        return $this->createQueryBuilder('b')
                    ->select('b.id','b.numBooking','b.bookingCreatedAt','bf.numFlight','bu.lastName', 'bu.firstName', 'bu.picture','bs.numSeat')
                    ->leftJoin('b.flight', 'bf')
                    ->leftJoin('b.booker', 'bu')
                    ->leftJoin('b.seat','bs')
                    ->where('concat(bf.numFlight,b.numBooking,bu.firstName,bu.lastName) like :concat')
                    ->setParameter('concat','%'.$concat.'%')
                    ->orderBy($column,$order)
                    ->setFirstResult($offset)
                    ->getQuery()
                    ->getResult();
        
    }

     /**
      * Cette Fonction sert à l'administration
      * Permet de faire un select distinct sur l'id flight
      *
      * @param [type] $value
      */
      public function findUniqueIdFlight()
      {
          return $this->createQueryBuilder('b')
                      ->distinct()
                      ->leftJoin('b.flight', 'bf')
                      ->getQuery()
                      ->getResult()
          ;
      }

    /*
    public function findOneBySomeField($value): ?Booking
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
