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
     * @Route("/accueil/{id}", name = "_accueil")
     */
    public function accueilAdmin(ManagerRegistry $doctrine,$id): Response {

        $em = $doctrine->getManager();

        $userRepository = $em->getRepository('App:User');

        $user = $userRepository->find($id);

        $args = array(
            'type' => 'Admin',
            'id' => $user->getId(),
            'name' => $user->getName(),
            'firstname' => $user->getFirstName(),
            'birth_date' => $user->getBirthDate(),
            'paniers' => $user->getIdShoppingCart(),
        );

        return $this->render('view/viewAdmin.html.twig', $args);

    }

    /**
     * @Route ("/user-list/{id}" , name = "_user_list")
     */

    public function viewUserList(ManagerRegistry $doctrine,$id): Response {

        $em = $doctrine->getManager();
        $userRepository = $em->getRepository('App:User');

        $user = $userRepository->find($id);

        $users = $userRepository->findAll();
        $args = array(
            'id' => $user->getId(),
            'users' => $users,
        );
        return $this->render("view/viewAdminUserList.html.twig",$args);
    }


}
