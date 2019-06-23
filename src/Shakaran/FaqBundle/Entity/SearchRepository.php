<?php

namespace Shakaran\FaqBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Class SearchRepository
 *
 * @package Shakaran\FaqBundle\Entity
 */
class SearchRepository extends EntityRepository
{
    /**
     * @param int $max
     *
     * @return DoctrineCollection|null
     */
    public function retrieveMostPopular($max)
    {
        $query = $this->createQueryBuilder('s')
            ->orderBy('s.searchCount', 'DESC')
            ->setMaxResults($max)
            ->getQuery();

        return $query->getResult();
    }
}