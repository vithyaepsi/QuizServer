<?php

namespace App\Repository;

use App\Entity\Answer;
use App\Entity\Category;
use App\Entity\QuestionType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Answer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Answer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Answer[]    findAll()
 * @method Answer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnswerRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Answer::class);
    }


    //  retourne les réponses d'après leur Id
    //  $idArray est un tableau d'ids
    public function getAnswersById($idArray){

    }

    //  similaire à array_rand, mais renvoie la valeur ou un tableau de valeurs plutôt que des clés.
    //  peut recevoir $amount, correspondant au nombre de résultats retournés
    public static function array_rand_values($vals, $amount = 1){
        if(!empty($vals)){
            $Keys = array_rand($vals, $amount);

            if($amount == 1){
                return $vals[$Keys];
            }
            else{
                $randomAnswers = array();
                foreach($Keys as $aid){
                    array_push($randomAnswers, $vals[$aid]);
                }
                return $randomAnswers;
            }


        }
        else{
            return false;
        }


    }

    //  retourne toutes les mauvaises réponses possibles
    //  1er argument envoyé pour sélection de toutes les Answer sauf celle dont l'id est envoyé
    //  2ème argument pour sélection de toutes les réponses d'une seule catégorie, qui n'est pas celle dont l'id est envoyé.

    public function getWrongAnswersId($answerId = false, $categoryId = false, $exclusion = null){
        $qb = $this->createQueryBuilder('a');
        $qb->leftJoin("a.category", "c");
        $qb->select('a.id');
        //$qb->addSelect('a.text');

        if($answerId !== false){
            $qb->andWhere($qb->expr()->notIn('a.id', $answerId));
        }
        elseif($categoryId !== false){
            $qb->andWhere($qb->expr()->notIn('c.id', $categoryId));
        }


        return $qb
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @return Answer[] Returns an array of Answer objects
     */
    public function getRandomWrongAnswers(QuestionType $questionType, Answer $rightAnswer, $amount = 3) {


        if($questionType->getText() == "Trouvez l'intrus"){
            //  La bonne réponse fait partie d'une catégorie
            // les mauvaises réponses font partie d'une autre même catégorie (une catégorie unique mais pas celle de la bonne réponse)

            $wrongAnswerIdsResult = $this->getWrongAnswersId(false, $rightAnswer->getCategory()->getId());
            $em = $this->getEntityManager();
            $categoryRep = $em->getRepository(Category::class);
            $randomCategory = $categoryRep->getRandomCategory($rightAnswer->getCategory()->getId());
            $answers = $this->findByCategory($randomCategory);

            shuffle($answers);
            $randomAnswers = AnswerRepository::array_rand_values($answers, $amount);

            return $randomAnswers;
        }
        elseif($questionType->getText() == "Quel est le sujet de l'image ?"){
            //  Les mauvaises réponses sont toutes les réponses de la base sauf la bonne réponse.
            $wrongAnswerIdsResult = $this->getWrongAnswersId($rightAnswer->getId(), false);

            $answerIds = [];
            foreach($wrongAnswerIdsResult as $result){
                array_push($answerIds, $result["id"]);
            }

            shuffle($answerIds);
            $randomAnswerIds = AnswerRepository::array_rand_values($answerIds, $amount);

            $qb = $this->createQueryBuilder('a');
            $qb->andWhere($qb->expr()->In('a.id', $randomAnswerIds));
            //->setMaxResults(1)
            $query = $qb->getQuery();

            return $query->getResult();
        }


    }
}
