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

        $shoppingRepository =$em->getRepository('App:ShoppingCart');

        $shoppingCart = $shoppingRepository->findBy(array('id_user' => $id));

        $args = array(
            'type' => 'Utilisateur',
            'id' => $user->getId(),
            'name' => $user->getName(),
            'firstname' => $user->getFirstName(),
            'birth_date' => $user->getBirthDate(),
            'shopCarts' => $shoppingCart,
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
            return $this->redirectToRoute('user_accueil',['id' => $id]);
        }

        if ($form->isSubmitted())
            $this->addFlash('info', 'modification incorrect');

        $args = array(
            'id' => $id,
            'userForm' => $form->createView(),
        );

        return $this->render('/view/modifierProfileUser.html.twig', $args);
    }

    /**
     * @Route("/shoppingCart/{id}", name="_shopping_cart",requirements = { "id" : "[0-9]\d*" })
     */

    public function viewShoppingCart(ManagerRegistry $doctrine,int $id): Response {

        $em = $doctrine->getManager();

        $userRepository = $em->getRepository('App:User');
        $user = $userRepository->find($id);

        $shoppingRepository = $em->getRepository('App:ShoppingCart');
        $shoppingCart = $shoppingRepository->findBy(array('id_user' => $user));

        $args = array(
            'id' => $user->getId(),
            'shopCarts' => $shoppingCart,
        );
        return $this->render('/view/userShoppingCart.html.twig',$args);
    }

    /**
     * @Route("/deleteItem/{id_current}/{id_shop}", name="_delete_item", requirements = { "id" : "[0-9]\d*" } )
     */

    public function deleteItem(ManagerRegistry $doctrine,int $id_current,int $id_shop): Response {

        $em = $doctrine->getManager();

        $shoppingCartRepository = $em->getRepository('App:ShoppingCart');
        $shopCart = $shoppingCartRepository->find($id_shop);

        $ProductRepository = $em->getRepository('App:Product');

        $product = $ProductRepository->find($shopCart->getIdProduct());


        if (is_null($product)){
            $this->addFlash('info','Item : '.$product->getName().' : erreur suppression');
            throw new NotFoundHttpException('Item : '. $product->getName() . 'inexistant');
        }

        $product = $product->setStock( $product->getStock() + $shopCart->getNbProduct() );

        $em->remove($shopCart);
        $em->flush();
        $this->addFlash('info','Item : '. $product->getName() .' supprimé');

        return $this->redirectToRoute('user_shopping_cart',['id' => $id_current]);
    }

    /**
     * @Route("/commanderPanier/{id_current}", name="_commander" )
     */

    public function commandItems(ManagerRegistry $doctrine,int $id_current ): Response {

        $em = $doctrine->getManager();

        $shoppingCartRepository = $em->getRepository('App:ShoppingCart');

        $userShoppingCart = $shoppingCartRepository->findBy(array('id_user' => $id_current));

        if (is_null($userShoppingCart)){
            $this->addFlash('info','Commande : '.$id_current.' : erreur suppression');
            throw new NotFoundHttpException('Commande : '. $id_current. 'inexistant');
        }

        foreach ($userShoppingCart as $usc) {
            $em->remove($usc);
        }
        $em->flush();
        $this->addFlash('info','Commande : '. $id_current .' supprimé');

        return $this->redirectToRoute('user_shopping_cart',['id' => $id_current]);
    }

    /**
     * @Route("/viderPanier/{id_current}", name="_vider" )
     */

    public function videItems(ManagerRegistry $doctrine,int $id_current ): Response {

        $em = $doctrine->getManager();

        $shoppingCartRepository = $em->getRepository('App:ShoppingCart');
        $ProductRepository = $em->getRepository('App:Product');

        $userShoppingCart = $shoppingCartRepository->findBy(array('id_user' => $id_current));

        if (is_null($userShoppingCart)){
            $this->addFlash('info','Commande : '.$id_current.' : erreur suppression');
            throw new NotFoundHttpException('Commande : '. $id_current. 'inexistant');
        }

        foreach ($userShoppingCart as $usc) {

            $product = $ProductRepository->find($usc->getIdProduct());

            $product = $product->setStock( $product->getStock() + $usc->getNbProduct() );

            $em->remove($usc);

        }
        $em->flush();
        $this->addFlash('info','Commande : '. $id_current .' supprimé');

        return $this->redirectToRoute('user_shopping_cart',['id' => $id_current]);
    }

}
