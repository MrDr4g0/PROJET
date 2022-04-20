<?php

namespace App\Controller\NonUser;

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


class NonUserFormController extends AbstractController
{
    /**
     * @Route("/nonUser/accueil", name = "nonUser_accueil")
     */
    public function accueil(EntityManagerInterface $em, Request $request): Response
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
        return $this->render('/view/viewNonUser.html.twig', $args);
    }
}
