<?php

namespace App\Controller;

use App\Entity\Product;
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
        $user = $UserRepository->find(-1);

        if (is_null($user))
            return $this->redirectToRoute('nonUserAccueil');
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


    /**
    * @Route("/ajouterendur",  name = "_ajouterendur")
    */

    public function ajouterendurAction(ManagerRegistry $doctrine): Response{
        $em = $doctrine->getManager();

        $user = new User();
        $user -> setLogin("simon")
            ->setPassword("nomis")
            ->setName("Gepavue")
            ->setFirstName("Simon")
            ->setBirthDate(date_create("1976/04/01"))
            ->setIsAdmin(false)
            ->setIsSuperAdmin(false)
        ;
        dump($user);
        $em->persist($user);
        $em->flush();
        dump($user);

        return $this->redirectToRoute('accueil');
    }

    /**
     * @Route("/ajouterendurProduct",  name = "_ajouterendur")
     */

    public function ajouterendurProduct(ManagerRegistry $doctrine): Response{
        $em = $doctrine->getManager();

        $product = new Product();
        $product ->setName("Cahier - A4 - Petits Carreaux")
            ->setPrice(5.50)
            ->setStock(52)
        ;
        dump($product);
        $em->persist($product);
        $em->flush();
        dump($product);

        return $this->redirectToRoute('accueil');
    }

}
