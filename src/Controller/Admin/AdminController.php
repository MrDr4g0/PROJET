<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;
/**
 * @Route("/admin", name = "admin")
 */

class AdminController extends AbstractController
{

    /**
     * @Route("/index", name = "_index")
     */
    public function index(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/Admin/AdminController.php',
        ]);
    }

    /**
     * @Route("/accueil/{id}", name = "_accueil")
     */
    public function accueilAdmin(ManagerRegistry $doctrine,$id): Response {

        $em = $doctrine->getManager();

        $filmRepository = $em->getRepository('App:User');

        $user = $filmRepository->find($id);

        $args = array(
            'type' => 'Admin',
            'name' => $user->getName(),
            'firstname' => $user->getFirstName(),
        );

        return $this->render('layout/accueil.html.twig', $args);

    }
}
