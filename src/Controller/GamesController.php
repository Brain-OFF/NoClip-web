<?php

namespace App\Controller;
use App\Entity\Games;
use App\Form\GamesType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GamesController extends AbstractController
{


    /**
     * @Route("/games", name="games")
     */
    public function index(): Response
    {
        return $this->render('games/index.html.twig', [
            'controller_name' => 'GamesController',
        ]);
    }
    /**
     * @Route("/gamesF", name="gamesF")
     */
    public function index2(): Response
    {

        return $this->render('games/indexF.html.twig', [
            'controller_name' => 'GamesController'
        ]);
    }
    /**
     * @Route("/gameslist",name="gameslist")
     */
    public function list()
    {
        $classrooms= $this->getDoctrine()->
        getRepository(games::class)->findAll();
        return $this->render("games/index.html.twig",
            array('games'=>$classrooms));
    }

    /**
     * @Route("/gameslistF",name="gameslistF")
     */

    public function listF()
    {
        $classroom= $this->getDoctrine()->
        getRepository(Games::class)->findAll();
        return $this->render("games/indexF.html.twig",
            array('games'=>$classroom));
    }

    /**
     * @Route("/addgames",name="addgames")
     */
    public function add(Request$request ){
        $games= new Games();
        $form= $this->createForm(GamesType::class,$games);

        $form->handleRequest($request);

        if($form->isSubmitted() ){
            $em = $this->getDoctrine()->getManager();
            $em->persist($games);
            $em->flush();
            return $this->redirectToRoute("gameslist");
        }
        return $this->render("games/add.html.twig",array("formgames"=>$form->createView()));
    }
    /**
     * @Route("/removegames/{id}",name="removegames")
     */
    public function delete($id){
        $games= $this->getDoctrine()->getRepository(Games::class)->find($id);
        $em= $this->getDoctrine()->getManager();
        $em->remove($games);
        $em->flush();
        return $this->redirectToRoute("gameslist");
    }
    /**
     * @Route("/modifygames/{id}",name="modifygames")
     */
    public function update(Request $request,$id){
        $games= $this->getDoctrine()->getRepository(Games::class)->find($id);
        $form= $this->createForm(gamesType::class,$games);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute("gameslist");
        }
        return $this->render("games/modify.html.twig",array("formgames"=>$form->createView()));
    }
}
