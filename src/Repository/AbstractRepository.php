<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;

abstract class AbstractRepository extends ServiceEntityRepository
{
    protected function paginate(QueryBuilder $qb, $limit = 10, $offset = 0)
    {
        if (0 == $limit) {
            throw new \LogicException('$limit must be greater than 0.');
        }
        if (($limit) > 20) {
            throw new \LogicException('Le nombre maximum d\'élements retourner par requête est limité à 20.');
        }

        return $qb->getQuery()->getResult();
    }
}