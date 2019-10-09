<?php

namespace App\Repository;

use App\Entity\User;
use App\Repository\AbstractRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findUsersByClient($product, $order = 'asc', $limit = 10, $offset = 0, $clientId)
    {
        $qb = $this
            ->createQueryBuilder('u')
            ->join("u.client", "c")
            ->andWhere('c.id = :clientId')
            ->setParameter('clientId', $clientId)
            ->orderBy('u.id', $order)
            ->setFirstResult($offset)
            ->setMaxResults($limit)
        ;
        
        if ($product) {
            $qb
                ->join("u.phoneChoice", "p")
                ->andWhere('p.id= :product')
                ->setParameter('product', $product)
            ;
        }
        
        return $this->paginate($qb, $limit, $offset);
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
