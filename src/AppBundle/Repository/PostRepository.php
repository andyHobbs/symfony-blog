<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Post;
use AppBundle\Entity\Category;
use Doctrine\ORM\EntityRepository;

/**
 * PostRepository
 */
class PostRepository extends EntityRepository
{
    /**
     * Get Posts by parent Category
     *
     * @param Category $category
     *
     * @return Post[]
     */
    public function findByCategory(Category $category)
    {
        $qb = $this->createQueryBuilder('p');

        return $qb->where($qb->expr()->eq('p.category', ':category'))
            ->orderBy('p.createdAt', 'DESC')
            ->setParameter('category', $category)
            ->getQuery()
            ->getResult();
    }

    /**
     * Get latest Posts with Comment count
     *
     * @param integer $limit
     *
     * @return Post[]
     */
    public function getLatestPosts($limit = 20)
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

}
