<?php

namespace App\Repository;

use App\Repository\AbstractRepository;
use App\Entity\MobilePhone;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method MobilePhone|null find($id, $lockMode = null, $lockVersion = null)
 * @method MobilePhone|null findOneBy(array $criteria, array $orderBy = null)
 * @method MobilePhone[]    findAll()
 * @method MobilePhone[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MobilePhoneRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MobilePhone::class);
    }


    public function findProducts($user, $order = 'asc', $limit = 10, $offset = 0)
    {
        $qb = $this
            ->createQueryBuilder('p')
            ->orderBy('p.brand', $order)
        ;
        
        if ($user) {
            $qb
                ->where('p.user = :user')
                ->setParameter(user, $this->getUser()->getId())
            ;
        }
        
        return $this->paginate($qb, $limit, $offset);
    }

}
