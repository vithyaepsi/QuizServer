<?php

namespace App\Repository;

use App\Entity\Question;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use function MongoDB\BSON\toJSON;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Question|null find($id, $lockMode = null, $lockVersion = null)
 * @method Question|null findOneBy(array $criteria, array $orderBy = null)
 * @method Question[]    findAll()
 * @method Question[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Question::class);
    }

//    /**
//     * @return Question[] Returns an array of Question objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('q.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Question
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    //  Récupère tous les ids des questions de la base
    public function getQuestionIds($exclusion = null){
        $qb = $this->createQueryBuilder('q');
        $qb->select('q.id');

        if($exclusion !== null){
            $qb->andWhere($qb->expr()->notIn('q.id', $exclusion));
        }

        return $qb
            ->getQuery()
            ->getResult()
            ;
    }

    public function getRandomQuestion($exclusion = null) : ?Question{
        $questionIdsResult = $this->getQuestionIds($exclusion);

        $questionIds = [];
        foreach($questionIdsResult as $result){
            array_push($questionIds, $result["id"]);
        }

        $randomQuestionId = array_rand($questionIds);


        return $this->createQueryBuilder('q')
            ->andWhere('q.id = :qid')
            ->setMaxResults(1)

            ->getQuery()
            ->setParameter("qid", $questionIds[$randomQuestionId])
            ->getOneOrNullResult()
            ;
    }
}
