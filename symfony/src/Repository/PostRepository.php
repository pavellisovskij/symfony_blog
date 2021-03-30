<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * @param string $title
     * @return Post[] Returns an array of Post objects
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findByTitleUsingLike(string $title)
    {
        return $this->createQueryBuilder('p')
            ->Where('p.title LIKE :title')
            ->setParameter('title', '%' . $title . '%')
            ->orderBy('p.title', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

     /**
      * @param string $name
      */
    public function findByCategoryName(string $name)
    {
        return $this->getEntityManager()
            ->createQuery('
                SELECT p
                FROM App\Entity\Post p
                INNER JOIN p.categories c
                WHERE c.name LIKE :name
                ORDER BY p.title ASC
            ')
            ->setParameter('name', "%$name%")
            ->getResult()
        ;
    }

    public function findByContent(string $str)
    {
        return $this->createQueryBuilder('p')
            ->Where('p.content LIKE :str')
            ->setParameter('str', '%' . $str . '%')
            ->orderBy('p.title', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByUser(string $username)
    {
        return $this->getEntityManager()
            ->createQuery('
                SELECT p
                FROM App\Entity\Post p
                INNER JOIN p.admin a
                WHERE a.username LIKE :username
                ORDER BY p.title ASC
            ')
            ->setParameter('username', "%$username%")
            ->getResult()
        ;
    }

    public function findByRating(int $grade)
    {
        switch ($grade) {
            case 0:
                $str = "WHERE (p.sum_of_grades / p.number_of_grades >= 0.0000 
                        AND p.sum_of_grades / p.number_of_grades <= 0.9999)
                        OR p.number_of_grades = 0";
                break;
            case 1:
                $str = "WHERE p.sum_of_grades / p.number_of_grades >= 1.000 
                        AND p.sum_of_grades / p.number_of_grades <= 1.9999";
                break;
            case 2:
                $str = "WHERE p.sum_of_grades / p.number_of_grades >= 2.0000 
                        AND p.sum_of_grades / p.number_of_grades <= 2.9999";
                break;
            case 3:
                $str = "WHERE p.sum_of_grades / p.number_of_grades >= 3.0000 
                AND p.sum_of_grades / p.number_of_grades <= 3.9999";
                break;
            case 4:
                $str = "WHERE p.sum_of_grades / p.number_of_grades >= 4.0000 
                AND p.sum_of_grades / p.number_of_grades <= 4.9999";
                break;
            case 5:
                $str = "WHERE p.sum_of_grades / p.number_of_grades = 5";
                break;
            default:
                $str = "WHERE p.sum_of_grades / p.number_of_grades IS NULL";
        }

        return $this->getEntityManager()->createQuery("
                SELECT p
                FROM App\Entity\Post p
                $str
                ORDER BY p.title ASC
            ")
            ->getResult()
        ;
    }

    public function findAllAndSortByDate()
    {
        return $this->createQueryBuilder('p')
            ->where('p.status = 1')
            ->orderBy('p.created_at', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    // /**
    //  * @return Comment[] Returns an array of Comment objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Comment
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
