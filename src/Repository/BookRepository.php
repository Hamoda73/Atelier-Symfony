<?php

namespace App\Repository;

use App\Entity\Book;
use App\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 *
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    

    public function searchbyref($ref) {
        return $this->createQueryBuilder('a')
                    ->where('a.ref LIKE :ref')
                    ->setParameter('ref', $ref)
                    ->getQuery()
                    ->getResult();
    }



    public function orderbyauthor()
    {
        return $this->createQueryBuilder('b')
            ->leftJoin('b.author', 'a')
            ->orderBy('a.username','Asc')
            ->getQuery()
            ->getResult();
    }

    public function listepublie()
{
        return $this->createQueryBuilder('b')
            ->leftJoin('b.author', 'a')
            ->where('b.publicationdate < :date')
            ->andWhere('a.nb_books > :numBooks')
            ->setParameter('date', new \DateTime('2023-01-01'))
            ->setParameter('numBooks', 35)
            ->getQuery()
            ->getResult();
}

/*public function sommescifi()
{
    $em = $this->getEntityManager();
    $query=$em
        ->createQuery('SELECT b FROM App\Entity\Book b WHERE b.category = :category');
    return $query->getSingleScalarResult();
}*/

public function sommescifi() {
    $em = $this->getEntityManager();
    $query = $em->createQuery('SELECT COUNT(b.ref) FROM App\Entity\Book b WHERE b.category = :category');
    $query->setParameter('category', 'Science-Fiction');
    return $query->getSingleScalarResult();
}

public function listedate() {
    $em = $this->getEntityManager();
    $query = $em->createQuery('SELECT b FROM App\Entity\Book b WHERE b.publicationdate BETWEEN :startDate AND :endDate');
        $query->setParameter('startDate', '2014-01-01');
        $query->setParameter('endDate', '2018-12-31');

        return $query->getResult();
}








//    /**
//     * @return Book[] Returns an array of Book objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Book
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
