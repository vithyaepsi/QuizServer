<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Match;
use App\Entity\Question;
use App\Entity\Round;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ApiController extends Controller
{
    /**
     * @Route("/api", name="api")
     */
    public function index()
    {
        $matchRep = $this->getDoctrine()->getRepository(Match::class);
        $match = $matchRep->getRandomFullMatch();

        return $this->json([
                "status" => "ok",
                "message" => "t'es moche",
                "data" => $match
            ]
        );
    }


    //  Créé un nouveau match composé de X rounds
    //  chaque round possède une question aléatoirement choisie dans la base de données (il ne doit pas y avoir de répétition)
    //  la bonne réponse à la question est déjà liée à la question
    //  il faut alors définir 3 mauvaises réponses en fonction de la bonne réponse.
    /**
     * @Route("/api/newMatch", name="api_randMatch")
     */
    public function playRandomMatch(){
        $newMatch = new Match();
        $questionRep = $this->getDoctrine()->getRepository(Question::class);
        $answerRep = $this->getDoctrine()->getRepository(Answer::class);
        $em = $this->getDoctrine()->getManager();
        $roundNb = 3;

        //$question = $questionRep->getRandomQuestion();
        //return $this->json(["data" => $question]);

        $ignorelist = [];


        for($i = 0; $i < $roundNb; $i++){
            $round = new Round();
            $question = $questionRep->getRandomQuestion(empty($ignorelist) ? null : $ignorelist);
            $round->setQuestion($question);
            array_push($ignorelist, $question->getId());

            //  get the right answer
            $answers = array();
            array_push($answers, $question->getAnswer());

            // get 3 wrong answers and the right answer, shuffle and add to round
            $wrongAnswers = $answerRep->getRandomWrongAnswers($question->getQuestionType(), $question->getAnswer());
            foreach($wrongAnswers as $ans){
                array_push($answers, $ans);
            }
            shuffle($answers);

            foreach($answers as $ans){
                //  ajoute les réponses au round
                $round->addAnswer($ans);
            }


            $em->persist($round);

            $newMatch->addRound($round);
        }

        $newMatch->setName("random shit");
        $em->persist($newMatch);

        $em->flush();


        return $this->json([
                "status" => "ok",
                "message" => "Le nouveau match a été enregistré en base.",
                "data" => $newMatch
            ]
        );

    }
    /**
     * @Route("/api/test", name="api_test")
     */
    public function test(){

    }
}
