<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    #[Route('/accueil', name: 'app_accueil')]
    /**
     * @Route("", name = "accueil")
     */
    public function index(): Response
    {
        return new Response("
            <body>
                <p>Bienvenu sur notre Site Symfony !</p>
                <a></a>
            </body>
            ");
    }
}
