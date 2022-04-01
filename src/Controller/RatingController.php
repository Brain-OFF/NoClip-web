<?php

namespace App\Controller;

use App\Entity\Games;
use App\Entity\Promos;
use App\Entity\Rating;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RatingController extends AbstractController
{
    /**
     * @Route("/rating", name="app_rating")
     */
    public function index(): Response
    {
        return $this->render('rating/index.html.twig', [
            'controller_name' => 'RatingController',
        ]);
    }
    /**
     * @Route("/gameslistFh",name="gameslistFh")
     */

    public function listF()
    {

        $promos=$this->getDoctrine()->
        getRepository(Promos::class)->findAll();
        $rating=$this->getDoctrine()->
        getRepository(Rating::class)->findAll();
        $classroom= $this->getDoctrine()->
        getRepository(Games::class)->findAll();
        return $this->render("games/indexF.html.twig",
            array('games'=>$classroom,'promos'=>$promos,'rating'=>$rating));
    }
    /**
     * @Route("/addrating1/{id}",name="addrating1")
     */
    public function add($id ){

        $games= new rating();
        $games->setIduser($this->getUser());
        $games->setIdgame($this->getDoctrine()->getRepository(Games::class)->find($id));
        $games->setNote(1);



            $em = $this->getDoctrine()->getManager();
            $em->persist($games);
            $em->flush();

        return $this->redirectToRoute("gameslistF");
    }
    /**
     * @Route("/addrating2/{id}",name="addrating2")
     */
    public function adda($id ){
        $games= new rating();
        $games->setIduser($this->getUser());
        $games->setIdgame($this->getDoctrine()->getRepository(Games::class)->find($id));
        $games->setNote(2);



        $em = $this->getDoctrine()->getManager();
        $em->persist($games);
        $em->flush();

        return $this->render("games/indexF.html.twig");
    }
    /**
     * @Route("/addrating3/{id}",name="addrating3")
     */
    public function addb($id ){
        $games= new rating();
        $games->setIduser($this->getUser());
        $games->setIdgame($this->getDoctrine()->getRepository(Games::class)->find($id));
        $games->setNote(3);



        $em = $this->getDoctrine()->getManager();
        $em->persist($games);
        $em->flush();

        return $this->render("games/indexF.html.twig");
    }
    /**
     * @Route("/addrating4/{id}",name="addrating4")
     */
    public function addc($id ){
        $games= new rating();
        $games->setIduser($this->getUser());
        $games->setIdgame($this->getDoctrine()->getRepository(Games::class)->find($id));
        $games->setNote(4);



        $em = $this->getDoctrine()->getManager();
        $em->persist($games);
        $em->flush();

        return $this->render("games/indexF.html.twig");
    }
    /**
     * @Route("/addrating5/{id}",name="addrating5")
     */
    public function addd($id )
    {
        $games = new rating();
        $games->setIduser($this->getUser());
        $games->setIdgame($this->getDoctrine()->getRepository(Games::class)->find($id));
        $games->setNote(5);


        $em = $this->getDoctrine()->getManager();
        $em->persist($games);
        $em->flush();

        return $this->render("games/indexF.html.twig");
    }
}
