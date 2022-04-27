<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\ShoppingCart;
use App\Form\ServiceType;
use App\Form\UserFormType;
use App\Service\InverseWordService;
use ContainerAjxZwdo\getConsole_ErrorListenerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
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
        $user = $UserRepository->find(2);

        if (is_null($user))
            return $this->redirectToRoute('nonUserAccueil');
        else if ($user->getIsAdmin())
            return $this->redirectToRoute('admin_accueil',array(
                'id' => $user->getId(),
            ));
        else if ($user->getIsSuperAdmin())
            return $this->redirectToRoute('super_admin_accueil',array(
                'id' => $user->getId(),
            ));
        else
            return $this->redirectToRoute('user_accueil',array(
                'id' => $user->getId(),
            ));
    }


    /**
    * @Route("/ajouterendur",  name = "ajouterendur")
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
     * @Route("/ajouterendurProduct",  name = "ajouterendur")
     */

    public function ajouterendurProduct(ManagerRegistry $doctrine): Response{
        $em = $doctrine->getManager();

        $product = new Product();
        $product ->setName("Crayon Rouge")
            ->setPrice(1.50)
            ->setStock(1863)
        ;
        dump($product);
        $em->persist($product);
        $em->flush();
        dump($product);

        return $this->redirectToRoute('accueil');
    }

    /**
     * @Route("/ajoutShoppingCart", name="ajouterendurSC")
     */

    public function ajouterendurShopping(ManagerRegistry $doctrine): Response{
        $em = $doctrine->getManager();
        $UserRepository = $em->getRepository('App:User');
        $ProductRepository = $em->getRepository('App:Product');

        $user = $UserRepository->find(2);
        $product = $ProductRepository->find(1);

        $shoppingCart = new ShoppingCart();
        $shoppingCart ->setIdUser($user)
            ->setIdProduct($product)
            ->setNbProduct(3)
        ;
        dump($shoppingCart);
        $em->persist($shoppingCart);
        $em->flush();
        dump($shoppingCart);

        return $this->redirectToRoute('accueil');
    }




    /**
     * @Route("/service" , name="service")
     */
    public function serviceWord(Request $request,InverseWordService $inv){

        $form = $this->createForm(ServiceType::class);
        $form->add('Send', SubmitType::class, ['label' => 'valider']);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $res = $inv->inverseWord($form->get('word')->getData());
            $this->addFlash('info', 'modification réussie');
            return $this->redirectToRoute('vue',['word' => $res]);
        }

        if ($form->isSubmitted())
            $this->addFlash('info', 'utilisateur incorrect');

        $args = array(
            'serviceForm' => $form->createView(),
        );

        return $this->render('/view/service.html.twig', $args);
    }

    /**
     * @Route ("/vue/{word}",name="vue")
     */
    public function viewAction(string $word) : Response {

        $args = array(
            'word' => $word,
        );

        return $this->render("view/serviceResult.html.twig",$args);

    }

}
