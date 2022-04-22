<?php

namespace App\Controller\NonUser;

use App\Form\AuthFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Form\UserFormType;
use App\Entity\User; //film
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Validator\ValidatorInterface; //validator
use Symfony\Component\Form\FormInterface;


class NonUserController extends AbstractController
{
    /**
     * @Route("/accueil_nonUser", name = "nonUserAccueil")
     */
    public function accueil(): Response
    {
        return $this->render('/view/viewNonUser.html.twig');
    }

    /**
     * @Route("/createAccount", name = "createAccount")
     */
    public function createAccount(EntityManagerInterface $em, Request $request): Response
    {
        $user = new User();

        $form = $this->createForm(UserFormType::class, $user);
        $form->add('Send', SubmitType::class, ['label' => 'Créer un compte']);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em->persist($user);
            $em->flush();
            $this->addFlash('info', 'Création de compte réussie');
            return $this->redirectToRoute('accueil');
        }

        if ($form->isSubmitted())
            $this->addFlash('info', 'Création de compte incorrecte');
        $args = array('nonUserForm' => $form->createView());
        return $this->render('/view/viewCreateOrLogin.html.twig', $args);
    }

    /**
     * @Route("/authentification", name = "authentification")
     */
    public function authentification(EntityManagerInterface $em, Request $request): Response
    {
        $userRepository = $em->getRepository('App:User');

        $form = $this->createForm(AuthFormType::class);
        $form->add('Send', SubmitType::class, ['label' => "S'authentifier"]);
        $form->handleRequest($request);

        if($form->isSubmitted()&& $form->isValid())
        {
            // recherche dans la table un utilisateur avec login et le password réuni
            // pour vérifier que l'utilisateur existe bien
            $user = $userRepository->findOneBy([$form->getData()]);
            if(is_null($user))
                $this->addFlash('info', 'Authentification incorrecte');
            else
            {
                $this->addFlash('info', 'Authentification réussie');
                return $this->redirectToRoute('accueil');
            }
        }

        $args = array('nonUserForm' => $form->createView());
        return $this->render('/view/viewCreateOrLogin.html.twig', $args);
    }
}
