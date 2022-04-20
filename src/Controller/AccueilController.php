<?php

namespace App\Controller;

use ContainerAjxZwdo\getConsole_ErrorListenerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;

class AccueilController extends AbstractController
{
    // cette action scanne l'utilisateur et lui renvoie la vue avec son rôle
    /**
     * @Route("", name = "accueil")
     */
    public function accueil(ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $UserRepository = $em->getRepository('App:User');
        // on met en dur l'id de l'utilisateur recherché
        $user = $UserRepository->find(0);

        if (is_null($user))
            return $this->redirectToRoute('nonUser_accueil');
        else if ($user->getIsAdmin())
            return $this->redirectToRoute('admin_accueil',array(
                'id' => $user->getId(),
            ));
        else if ($user->getIsSuperAdmin())
            return $this->redirectToRoute('superAdmin_accueil',array(
                'id' => $user->getId(),
            ));
        else
            return $this->redirectToRoute('user_accueil',array(
                'id' => $user->getId(),
            ));
    }
}
