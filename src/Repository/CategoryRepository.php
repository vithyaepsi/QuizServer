<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Category::class);
    }

//    /**
//     * @return Category[] Returns an array of Category objects
//     */
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
    public function findOneBySomeField($value): ?Category
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    //  Récupère tous les ids des questions de la base
    public function getCategoryIds($exclusion = null){
        $qb = $this->createQueryBuilder('c');
        $qb->select('c.id');

        if($exclusion !== null){
            $qb->andWhere($qb->expr()->notIn('c.id', $exclusion));
        }

        return $qb
            ->getQuery()
            ->getResult()
            ;
    }

    //  $exclusion contient un id à exclure de la recherche
    public function getRandomCategory($exclusion = null) : ?Category {
        $categoryIdsResult = $this->getCategoryIds($exclusion);

        $categoryIds = [];
        foreach($categoryIdsResult as $result){
            array_push($categoryIds, $result["id"]);
        }

        $randomCategoryId = array_rand($categoryIds);


        return $this->createQueryBuilder('c')
            ->andWhere('c.id = :cid')
            ->setMaxResults(1)

            ->getQuery()
            ->setParameter("cid", $categoryIds[$randomCategoryId])
            ->getOneOrNullResult()
            ;
    }
}
