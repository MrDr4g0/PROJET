<?php

namespace App\Controller\User;

use App\Form\UserFormType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Route("/user", name = "user")
 */

class UserController extends AbstractController
{
    /**
     * @Route("/accueil/{id}", name = "_accueil",requirements = { "id" : "[0-9]\d*"})
     */
    public function accueilAdmin(ManagerRegistry $doctrine,int $id): Response {

        $em = $doctrine->getManager();

        $userRepository = $em->getRepository('App:User');

        $user = $userRepository->find($id);

        $args = array(
            'type' => 'Utilisateur',
            'id' => $user->getId(),
            'name' => $user->getName(),
            'firstname' => $user->getFirstName(),
            'birth_date' => $user->getBirthDate(),
            'paniers' => $user->getIdShoppingCart(),
        );

        return $this->render('view/user.html.twig', $args);

    }

    /**
     * @Route ("/productList/{id}" , name = "_product_list",requirements = { "id" : "[0-9]\d*"})
     */

    public function viewProductList(ManagerRegistry $doctrine,int $id): Response {

        $em = $doctrine->getManager();
        $productRepository = $em->getRepository('App:Product');

        $userRepository = $em->getRepository('App:User');
        $user = $userRepository->find($id);

        $products = $productRepository->findAll();
        $args = array(
            'id' => $user->getId(),
            'products' => $products,
        );
        return $this->render("view/userProductList.html.twig",$args);
    }

    /**
     * @Route ("/modifierProfil/{id}" , name="_change_profil",requirements = { "id" : "[0-9]\d*" } )
     */
    public function modifierProfil(EntityManagerInterface $em, Request $request,int $id): Response
    {

        $userRepository = $em->getRepository('App:User');
        $user = $userRepository->find($id);

        if (is_null($user))
            throw new NotFoundHttpException('Le profil n\'existe pas');


        $form = $this->createForm(UserFormType::class,$user);
        $form->add('Send', SubmitType::class, ['label' => 'change']);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em->flush();
            $this->addFlash('info', 'modification réussie');
            return $this->redirectToRoute('super_admin_accueil',['id' => $id]);
        }

        if ($form->isSubmitted())
            $this->addFlash('info', 'modification incorrect');

        $args = array(
            'id' => $id,
            'userForm' => $form->createView(),
        );

        return $this->render('/view/modifierProfileUser.html.twig', $args);
    }
}
