<?php

namespace App\Controller\SuperAdmin;

use App\Entity\User;
use App\Form\ProductFormType;
use App\Form\UserFormType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/superAdmin", name = "super_admin")
 */

class SuperAdminController extends AbstractController
{
    /**
     * @Route("/accueil/{id}", name = "_accueil",requirements = { "id" : "[0-9]\d*"})
     */
    public function accueilAdmin(ManagerRegistry $doctrine,int $id): Response {

        $em = $doctrine->getManager();

        $userRepository = $em->getRepository('App:User');

        $user = $userRepository->find($id);

        $args = array(
            'type' => 'Super-Administrateur',
            'id' => $user->getId(),
            'name' => $user->getName(),
            'firstname' => $user->getFirstName(),
            'birth_date' => $user->getBirthDate(),
            'paniers' => $user->getIdShoppingCart(),
        );

        return $this->render('view/viewSuperAdmin.html.twig', $args);

    }
    /**
     * @Route ("/ajoutAdmin/{id}" , name="_add_admin",requirements = { "id" : "[0-9]\d*" } )
     */
    public function ajoutAdmin(EntityManagerInterface $em, Request $request,int $id): Response
    {

        $user = new User();

        $user->setIsAdmin(true);

        $form = $this->createForm(UserFormType::class,$user);
        $form->add('Send', SubmitType::class, ['label' => 'ajouter']);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em->persist($user);
            $em->flush();
            $this->addFlash('info', 'ajout réussie');
            return $this->redirectToRoute('super_admin_accueil',['id' => $id]);
        }

        if ($form->isSubmitted())
            $this->addFlash('info', 'utilisateur incorrect');

        $args = array(
            'id' => $id,
            'userForm' => $form->createView(),
        );

        return $this->render('/view/viewAddAdmin.html.twig', $args);
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

        return $this->render('/view/viewAddAdmin.html.twig', $args);
    }
}
