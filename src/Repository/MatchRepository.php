<?php

namespace App\Repository;

use App\Entity\Answer;
use App\Entity\Match;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Match|null find($id, $lockMode = null, $lockVersion = null)
 * @method Match|null findOneBy(array $criteria, array $orderBy = null)
 * @method Match[]    findAll()
 * @method Match[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MatchRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Match::class);
    }

    public function getFullMatch($id){
        $qb = $this->createQueryBuilder("m");
        $qb->andWhere("m.id = :idd");

        $qb->leftJoin("m.rounds", "r");
        $qb->addSelect('r');

        $qb->leftJoin("r.question", "q");
        $qb->addSelect('q');


        $query = $qb->getQuery();
        $query->setParameter("idd", $id);
        $result = $query->getResult();

        return $result;
    }

    public function getRandomFullMatch(){
        $matchIdIds = $this->getMatchIds();
        
        $matchIds = [];
        foreach($matchIdIds as $result){
            array_push($matchIds, $result["id"]);
        }
        $selectedMatchId = AnswerRepository::array_rand_values($matchIds);

        //print_r($selectedMatchId);

        $fullMatch = $this->getFullMatch($selectedMatchId);
        return $fullMatch;
    }

    //  Récupère tous les ids des matches de la base
    public function getMatchIds($exclusion = null){
        $qb = $this->createQueryBuilder('m');
        $qb->select('m.id');

        if($exclusion !== null){
            $qb->andWhere($qb->expr()->notIn('m.id', $exclusion));
        }

        return $qb
            ->getQuery()
            ->getResult()
            ;
    }

}
