<?php

namespace App\Controller;

use App\Entity\Games;
use App\Entity\Promos;
use App\Form\GamesType;
use App\Form\PromosType;
use App\Repository\PromosRepository;
use Cassandra\Date;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PromosController extends AbstractController
{
    /**
     * @Route("/promos", name="promos")
     */
    public function index(): Response
    {
        return $this->render('promos/index.html.twig', [
            'controller_name' => 'PromosController',
        ]);
    }
    /**
     * @Route("/promoslist",name="promoslist")
     */
    public function list()
    {
        $classrooms= $this->getDoctrine()->
        getRepository(Promos::class)->findAll();
        return $this->render("promos/index.html.twig",
            array('promos'=>$classrooms));
    }
    /**
     * @Route("/addpromos",name="addpromos")
     */
    public function add(Request$request ){
        $games= new Promos();
        $form= $this->createForm(PromosType::class,$games);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() ){
            $em = $this->getDoctrine()->getManager();
            $em->persist($games);
            $em->flush();
            return $this->redirectToRoute("promoslist");
        }
        return $this->render("promos/add.html.twig",array("formpromos"=>$form->createView()));
    }
    /**
     * @Route("/countdown/{date1}",name="countdown")
     */
    public function countdown( $date1) {
        $date2=strlen($date1);
        $remaining = strtotime($date2) -time();
        $days_remaining = floor($remaining / 86400);

        $from = strtotime($date2);
        $today = time();
        echo "   $today      ******* $from *******************       ";
        $difference = $today - $from;
        echo floor($difference / 86400);  // (60 * 60 * 24)
        return $this->render("games/countd.html.twig",
            array('days_remaining'=>$days_remaining));
    }
    /**
     * @Route("/showTr",name="showTr")
     */
    public function showTr(PromosRepository  $T)
    {
        $TriDate =$T->orderByDate();
        return $this->render("promos/AffichTri.html.twig", array("TriDate"=>$TriDate));
    }
}
