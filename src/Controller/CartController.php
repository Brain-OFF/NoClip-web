<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Games;
use App\Entity\Products;
use App\Repository\GamesRepository;
use App\Repository\ProductsRepository;
use http\Params;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/panier", name="panier")
     */

    public function index(SessionInterface $session , GamesRepository  $gamesRepository): Response
    {
        $panier = $session->get('panier', []);
        $panierwithData = [];

        $total = 0;
        Foreach ($panier as $id => $quantity) {
            $product = $gamesRepository->find($id);
        $panierwithData[] = [


            'product' => $gamesRepository->find($id),
            'quantity' => $quantity
            ];
            $total += $product->getPrix() * $quantity;
        }


           return $this->render('cart/index.html.twig', compact("panierwithData", "total"));

    }

    /**
     * @Route("/add/{id}", name="add")
     */
    public function add( $id, SessionInterface $session)
    {
        $panier = $session->get('panier', []);
    if (!empty($panier[$id])) {
        $panier[$id]++;
     } else {
        $panier[$id] = 1;
    }
        $session->set('panier', $panier);




        return $this->redirectToRoute("panier");

    }

    /**
     * @Route("/remove/{id}", name="remove")
     */
    public function remove(Games $product, SessionInterface $session)
    {

        $panier = $session->get("panier", []);
        $id = $product->getId();

        if(!empty($panier[$id])){
            if($panier[$id] > 1){
                $panier[$id]--;
            }else{
                unset($panier[$id]);
            }
        }


        $session->set("panier", $panier);

        return $this->redirectToRoute("panier");
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Games $product, SessionInterface $session)
    {

        $panier = $session->get("panier", []);
        $id = $product->getId();

        if(!empty($panier[$id])){
            unset($panier[$id]);
        }


        $session->set("panier", $panier);

        return $this->redirectToRoute("panier");
    }

    /**
     * @Route("/delete", name="delete_all")
     */
    public function deleteAll(SessionInterface $session)
    {
        $session->remove("panier");

        return $this->redirectToRoute("panier");
    }
}
