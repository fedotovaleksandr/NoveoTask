<?php

namespace ApiBundle\Repository;

use Doctrine\ORM\Query;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends \Doctrine\ORM\EntityRepository
{
    public function getRandomUsers($count = 1, $hydration = Query::HYDRATE_OBJECT)
    {
        $totalRowsTable = $this->createQueryBuilder('u')->select('count(u.id)')->getQuery()->getSingleScalarResult();
        return $this->createQueryBuilder('u')
            ->select('u')
            ->setMaxResults($count)
            ->setFirstResult(rand(0, $totalRowsTable - $count - 1))
            ->getQuery()
            ->getResult($hydration);
    }
}
