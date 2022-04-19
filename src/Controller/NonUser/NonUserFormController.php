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

class NonUserFormController extends AbstractController
{
    /**
     * @Route("/nonUser/accueil", name = "nonUser_accueil")
     */
    public function accueil(Request $request): Response
    {
        $form = $this->createForm(UserType::class);
        $form->add('Send', SubmitType::class, ['label' => 'Identification']);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            return $this->redirectToRoute('accueil');
        }
        else
            return
    }
}
