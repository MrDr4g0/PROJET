<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\ProductFormType;
use App\Form\ShoppingType;
use App\Form\UserFormType;
use Doctrine\Common\Collections\Collection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
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

        $shoppingRepository =$em->getRepository('App:ShoppingCart');

        $shoppingCart = $shoppingRepository->findBy(array('id_user' => $id));

        $args = array(
            'type' => 'Administrateur',
            'id' => $user->getId(),
            'name' => $user->getName(),
            'firstname' => $user->getFirstName(),
            'birth_date' => $user->getBirthDate(),
            'shopCarts' => $shoppingCart,
        );

        return $this->render('view/admin.html.twig', $args);

    }

    /**
     * @Route ("/userList/{id}" , name = "_user_list",requirements = { "id" : "[0-9]\d*"})
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
        return $this->render("view/adminUserList.html.twig",$args);
    }

    /**
     * @Route ("/userDelete/{id_current}/{id_delete}", name = "_delete")
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
        $this->addFlash('info','User'.$id_delete.' supprim??');

        return $this->redirectToRoute('admin_user_list',['id' => $id_current]);
    }

    /**
     * @Route ("/productList/{id}" , name = "_product_list",requirements = { "id" : "[0-9]\d*"})
     */

    public function viewProductList(ManagerRegistry $doctrine,Request $request,int $id): Response {

        $em = $doctrine->getManager();
        $productRepository = $em->getRepository('App:Product');

        $userRepository = $em->getRepository('App:User');
        $user = $userRepository->find($id);

        $products = $productRepository->findAll();

        $shoppingCart = new ShoppingCart();

        $form = $this->createForm(ShoppingType::class,$shoppingCart);
        $form->handleRequest($request);

        $form->get('id_user')->setData($id);

        if($form->isSubmitted() && $form->isValid())
        {
            $em->persist($shoppingCart);
            $em->flush();
            $this->addFlash('info', 'ajout r??ussie');
            return $this->redirectToRoute('admin_accueil',['id' => $id]);
        }

        if ($form->isSubmitted())
            $this->addFlash('info', 'produit incorrect');

        $args = array(
            'id' => $id,
            'products' => $products,
            'shoppingForm' => $form->createView(),
        );

        return $this->render('/view/adminProductList.html.twig', $args);

    }

    /**
     * @Route ("/ajoutProduit/{id}" , name="_add_product",requirements = { "id" : "[0-9]\d*" } )
     */
    public function ajoutProduct(EntityManagerInterface $em, Request $request,int $id): Response
    {

        $product = new Product();

        $form = $this->createForm(ProductFormType::class,$product);
        $form->add('Send', SubmitType::class, ['label' => 'ajouter']);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em->persist($product);
            $em->flush();
            $this->addFlash('info', 'ajout r??ussie');
            return $this->redirectToRoute('admin_accueil',['id' => $id]);
        }

        if ($form->isSubmitted())
            $this->addFlash('info', 'produit incorrect');

        $args = array(
            'id' => $id,
            'productForm' => $form->createView(),
        );

        return $this->render('/view/addProduct.html.twig', $args);
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
        return $this->render('/view/adminShoppingCart.html.twig',$args);
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
        $this->addFlash('info','Item : '. $product->getName() .' supprim??');

        return $this->redirectToRoute('admin_shopping_cart',['id' => $id_current]);
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
        $this->addFlash('info','Commande : '. $id_current .' supprim??');

        return $this->redirectToRoute('admin_shopping_cart',['id' => $id_current]);
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
        $this->addFlash('info','Commande : '. $id_current .' supprim??');

        return $this->redirectToRoute('admin_shopping_cart',['id' => $id_current]);
    }

}
