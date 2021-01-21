<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
     * @param string $name
     * @return Category[] Returns an array of Category objects
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findByNameUsingLike(string $name): array
    {
        return $this->createQueryBuilder('c')
            ->Where('c.name LIKE :name')
            ->setParameter('name', "%$name%")
            ->orderBy('c.name', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param string $title
     * @return Category[] Returns an array of Category objects
     */
    public function findByPostTitleUsingLike(string $title): array
    {
        return $this->getEntityManager()
            ->createQuery('
                SELECT c
                FROM App\Entity\Category c
                INNER JOIN c.posts p
                WHERE p.title LIKE :title
                ORDER BY c.name ASC
            ')
            ->setParameter('title', "%$title%")
            ->getResult()
        ;
    }

    /**
     * @return array
     */
    public function findAllSorted(): array
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.name', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
