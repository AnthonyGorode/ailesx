<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Cette fonction sert à l'administration
     * Permet d'afficher la liste des utilisateurs
     *
     * @param [type] $column
     * @param [type] $order
     * @param [type] $limit
     * @param [type] $offset
     * @param [type] $concat
     * @return void
     */
    public function adminArray($column,$order,$limit,$offset,$concat){
        // Si il n'y a pas de recherche effectuée affiche tous les utilisateurs
        // Sinon affiche tous les utilisateurs en fonction de la recherche
        if(empty($concat)){
            return $this->createQueryBuilder('u')
                        ->leftJoin('u.userRoles','ur')
                        ->orderBy($column,$order)
                        ->setMaxResults($limit)
                        ->setFirstResult($offset)
                        ->getQuery()
                        ->getResult();
        }else{
            return $this->createQueryBuilder('u')
                        ->leftJoin('u.userRoles','ur')
                        ->where('concat(u.firstName,u.lastName,u.email) like :concat')
                        ->setParameter('concat','%'.$concat.'%')
                        ->orderBy($column,$order)
                        ->setMaxResults($limit)
                        ->setFirstResult($offset)
                        ->getQuery()
                        ->getResult();
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
        return $this->createQueryBuilder('u')
                    ->leftJoin('u.userRoles','ur')
                    ->where('concat(u.firstName,u.lastName,u.email) like :concat')
                    ->setParameter('concat','%'.$concat.'%')
                    ->orderBy($column,$order)
                    ->setFirstResult($offset)
                    ->getQuery()
                    ->getResult();
        
    }

    /**
     * Permet de retourner tous les utilisateurs connectés
     *
     * @return void
     */
    public function getActive()
	{
		// Comme vous le voyez, le délais est redondant ici, l'idéale serait de le rendre configurable via votre bundle
		$delay = new \DateTime();
		$delay->setTimestamp(strtotime('2 minutes ago'));

		return $this->createQueryBuilder('u')
			->where('u.lastActivity > :delay')
			->setParameter('delay', $delay)
            ->getQuery()
            ->getResult()
		;
	}

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
