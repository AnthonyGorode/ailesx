<?php

namespace App\Repository;

use App\Entity\Airport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Airport|null find($id, $lockMode = null, $lockVersion = null)
 * @method Airport|null findOneBy(array $criteria, array $orderBy = null)
 * @method Airport[]    findAll()
 * @method Airport[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AirportRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Airport::class);
    }

    /**
     * permet de selectionner l'id d'un aéroport
     * 
     * @return Airport[] Returns an array of Airport objects
     */
    public function findIdAirport($nameAirport)
    {
        return $this->createQueryBuilder('a')
            ->select('a.id')
            ->andWhere('a.nameAirport = :nameAirport')
            ->setParameter('nameAirport', $nameAirport)
            ->getQuery()
            ->getResult()
        ;
    }

     /**
     * permet de selectionner un aéroport
     * 
     * @return Airport[] Returns an array of Airport objects
     */
    public function findAirport($nameAirport)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.nameAirport = :nameAirport')
            ->setParameter('nameAirport', $nameAirport)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Cette fonction est utiliser pour l'administration
     * Permet d'afficher le tableau des aéroport et de leur ville
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
            return $this->createQueryBuilder('a')
                        ->select('a.id','a.nameAirport','ac.nameCity')
                        ->join('a.city', 'ac')
                        ->orderBy($column,$order)
                        ->setMaxResults($limit)
                        ->setFirstResult($offset)
                        ->getQuery()
                        ->getResult()
                    ;
        }else{
            return $this->createQueryBuilder('a')
                        ->select('a.id','a.nameAirport','ac.nameCity')
                        ->join('a.city', 'ac')
                        ->where('concat(a.nameAirport,ac.nameCity) like :concat')
                        ->setParameter('concat','%'.$concat.'%')
                        ->orderBy($column,$order)
                        ->setMaxResults($limit)
                        ->setFirstResult($offset)
                        ->getQuery()
                        ->getResult()
                    ; 
        }
    }

    /*
    public function findOneBySomeField($value): ?Airport
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
