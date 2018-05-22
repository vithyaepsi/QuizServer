<?php

namespace App\Controller;

use App\Entity\Match;
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
                "message" => "",
                "data" => $matchUno
            ]
        );
    }
}
