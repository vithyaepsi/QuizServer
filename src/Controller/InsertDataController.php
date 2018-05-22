<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Category;
use App\Entity\Match;
use App\Entity\Question;
use App\Entity\QuestionType;
use App\Entity\Round;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class InsertDataController extends Controller
{
    /**
     * @Route("/insert/data", name="insert_data")
     */
    public function index()
    {


        $em = $this->getDoctrine()->getManager();


        $category1 = new Category();
        $category1->setName("Légumes");
        $em->persist($category1);

        $category2 = new Category();
        $category2->setName("Animaux");
        $em->persist($category2);

        $category3 = new Category();
        $category3->setName("Personnes");
        $em->persist($category3);


        $answer1 = new Answer();
        $answer1->setText("Topinambour");
        $answer1->setCategory($category1);

        $answer2 = new Answer();
        $answer2->setText("Nicolas Sarkozy");
        $answer2->setCategory($category3);

        $answer3 = new Answer();
        $answer3->setText("Raton-laveur");
        $answer3->setCategory($category2);

        $answer4 = new Answer();
        $answer4->setText("Carotte");
        $answer4->setCategory($category1);

        $answer5 = new Answer();
        $answer5->setText("François Hollande");
        $answer5->setCategory($category3);

        $answer6 = new Answer();
        $answer6->setText("Loup");
        $answer6->setCategory($category2);

        $answer7 = new Answer();
        $answer7->setText("Haricot magique");
        $answer7->setCategory($category1);

        $answer8 = new Answer();
        $answer8->setText("Laurent Baffie");
        $answer8->setCategory($category3);

        $answer9 = new Answer();
        $answer9->setText("Furet");
        $answer9->setCategory($category2);

        $answer10 = new Answer();
        $answer10->setText("Salsifis");
        $answer10->setCategory($category1);

        $answer11 = new Answer();
        $answer11->setText("Philippe Risoli");
        $answer11->setCategory($category3);

        $answer12 = new Answer();
        $answer12->setText("Fouine");
        $answer12->setCategory($category2);

        $em->persist($answer1);
        $em->persist($answer2);
        $em->persist($answer3);
        $em->persist($answer4);
        $em->persist($answer5);
        $em->persist($answer6);
        $em->persist($answer7);
        $em->persist($answer8);
        $em->persist($answer9);
        $em->persist($answer10);
        $em->persist($answer11);
        $em->persist($answer12);

        $questiontype1 = new QuestionType();
        $questiontype1->setText("Trouvez l'intrus");
        $em->persist($questiontype1);

        $questiontype2 = new QuestionType();
        $questiontype2->setText("Quel est le sujet de l'image ?");
        $em->persist($questiontype2);


        $question1 = new Question();
        $question1->setAnswer($answer1);
        $question1->setImage("topinambour.png");
        $question1->setQuestionType($questiontype2);
        $em->persist($question1);

        $question2 = new Question();
        $question2->setAnswer($answer9);
        $question2->setImage("furet.png");
        $question2->setQuestionType($questiontype2);
        $em->persist($question2);


        $round1 = new Round();
        //$round1->setMatches($match);
        $round1->setQuestion($question1);
        $round1->addAnswer($answer5);
        $round1->addAnswer($answer1);
        $round1->addAnswer($answer7);
        $round1->addAnswer($answer9);
        $em->persist($round1);

        $round2 = new Round();
        $round2->setQuestion($question2);
        $round2->addAnswer($answer3);
        $round2->addAnswer($answer5);
        $round2->addAnswer($answer10);
        $round2->addAnswer($answer9);
        $em->persist($round2);


        $match = new Match();
        $match->setName("Match 1");
        $match->addRound($round1);
        $match->addRound($round2);
        $em->persist($match);


        $em->flush();

        return $this->render('insert_data/index.html.twig', [
            'controller_name' => 'InsertDataController',
        ]);
    }
}
