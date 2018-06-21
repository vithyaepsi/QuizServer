<?php

namespace App\Controller;

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
        $matchUno = $matchRep->getFullMatch();

        return $this->json([
                "status" => "ok",
                "message" => "t'es moche",
                "data" => $matchUno
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
        $X = 2;

        for($i = 0; $i < $X; $i++){
            //  Génération d'un round
            $newRound = new Round();
            $question = $questionRep->getRandomQuestion();

            //$newRound->setQuestion($question);

            return $this->json([
               "status" => "ok",
               "message" => "random match generated",
               "data" => $question
            ]);

        }

    }
}
