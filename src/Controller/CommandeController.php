<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Form\CommandeType;
use App\Form\CommandeUPType;
use App\Repository\CommandeRepository;
use App\Repository\GamesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CommandeController extends AbstractController
{


    /**
     * @Route("/", name="commande_index", methods={"GET"})
     */
    public function index(CommandeRepository $commandeRepository): Response
    {

        return $this->render('commande/index.html.twig', [
            'commandes' => $commandeRepository->findAll(),
        ]);
    }
    /**
     * @Route("/commandelist",name="commandelist")
     */


    public function list()
    {
        $Commandes= $this->getDoctrine()->
        getRepository(Commande::class)->findAll();
        return $this->render("commande/index.html.twig",
            array('commandes'=>$Commandes));
    }


    /**
     * @Route("/new", name="commande_new", methods={"GET","POST"})
     */
    public function new(Request $request,SessionInterface $session, GamesRepository  $gamesRepository): Response
    {
        $commande = new Commande();
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);
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

        if ($form->isSubmitted() && $form->isValid()) {
            for ($i = 0; $i < count($panierwithData ); $i++) {

                $commande->setProduit($panierwithData [$i]['product']->getName());

                $commande->setQuantite($panierwithData [$i]['quantity']);

                $commande->setTotalcost($total);}

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($commande);
            $entityManager->flush();

            return $this->redirectToRoute('commandelist');
        }




        return $this->render('commande/new.html.twig',
            ['commande' => $commande,'panierwithData' => $panierwithData,'total'=>$total,'form'=>$form->createView()]);
    }



    /**
     * @param Request $request
     * @param int $id
     * @param CommandeRepository $repository
     * @return Response
     * @Route ("/modifiercommandeback/{id}",name="modifiercommandeback")
     */
    public function modify(Request $request, int $id,CommandeRepository $repository): Response
    {
        //$entityManager = $this->getDoctrine()->getManager();
        $commande=$repository->find($id);
        //$classroom = $entityManager->getRepository(Classroom::class)->find($id);
        $form = $this->createForm(CommandeUPType::class, $commande);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->flush();
            return $this->redirectToRoute('commandelist');

        }

        return $this->render("commande/modify.html.twig", [
            "fo" => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}", name="commande_delete", methods={"POST"})
     */
    public function delete(Request $request, Commande $commande): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commande->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($commande);
            $entityManager->flush();
        }

        return $this->redirectToRoute('commande_index');
    }




}
