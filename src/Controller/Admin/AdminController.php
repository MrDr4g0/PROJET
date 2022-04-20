<?php

namespace App\Controller\Admin;

use Doctrine\Common\Collections\Collection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;
use App\Entity\ShoppingCart;

/**
 * @Route("/admin", name = "admin")
 */

class AdminController extends AbstractController
{

    /**
     * @Route("/accueil/{id}", name = "_accueil",requirements = { "id" : "[0-9]\d*"})
     */
    public function accueilAdmin(ManagerRegistry $doctrine,int $id): Response {

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
     * @Route ("/user-list/{id}" , name = "_user_list",requirements = { "id" : "[0-9]\d*"})
     */

    public function viewUserList(ManagerRegistry $doctrine,int $id): Response {

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

    /**
     * @Route ("/user-delete/{id_current}/{id_delete}", name = "_delete")
     */

    public function deleteUser(ManagerRegistry $doctrine,int $id_current,int $id_delete): Response {

        $em = $doctrine->getManager();
        $userRepository = $em->getRepository('App:User');
        //$shoppingCartRepository = $em->getRepository('App:ShoppingCart');

        $user = $userRepository->find($id_delete);

        if (is_null($user)){
            $this->addFlash('info','User'.$id_delete.' : erreur suppression');
            throw new NotFoundHttpException('User'. $id_delete . 'inexistant');
        }

        if ($id_delete == $id_current){
            $this->addFlash('info','User'.$id_delete.' : erreur suppression');
            throw new NotFoundHttpException('User'. $id_delete . 'suppression de l\'utilisateur courant');
        }

        if ($user->getIsSuperAdmin()){
            $this->addFlash('info','User'.$id_delete.' : erreur suppression');
            throw new NotFoundHttpException('User'. $id_delete . 'suppression d\'un super administrateur interdit !');
        }

        //$shoppingCart = $shoppingCartRepository->findBy(array('id_user'=>$user));

        //$em->remove($shoppingCart);
        //$em->flush();
        $em->remove($user);
        $em->flush();
        $this->addFlash('info','User'.$id_delete.' supprimé');

        return $this->redirectToRoute('admin_user_list',['id' => $id_current]);
    }


}
