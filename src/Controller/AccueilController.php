<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;

class AccueilController extends AbstractController
{
    /**
     * @Route("/accueil", name = "accueil")
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

    /**
     * @Route("/ajouterendur",  name = "_ajouterendur")
     */
    public function ajouterendurAction(ManagerRegistry $doctrine): Response{
        $em = $doctrine->getManager();

        $user = new User();
        $user -> setLogin("simon")
            ->setPassword("nomis")
            ->setName("Gepalareff")
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
     * @Route("/modifierendur",  name = "_modifierendur")
     */
    public function modifierendurAction(ManagerRegistry $doctrine): Response{

        $id = 1;

        $em = $doctrine->getManager();

        $userRepository = $em->getRepository('App:User');

        $user = $userRepository>find($id);


        if(!(is_null($id))){
            $user->setQuantite($user->getQuantite()+10);
            $user->setPrix(15.98);
        }


        $em->flush();

        return $this->redirectToRoute('sandbox_doctrine_view',['id' => $id]);

    }

    /**
     * @Route("/effacerendur", name = "_effacerendur")
     */

    public function effacerendurAction(ManagerRegistry $doctrine): Response{
        $id = 3;
        $em = $doctrine->getManager();
        $userRepository = $em->getRepository('App:User');

        $user =$userRepository->find($id);

        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('accueil');
    }
}
