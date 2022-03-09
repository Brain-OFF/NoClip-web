<?php

namespace App\Controller;
use App\Entity\Games;
use App\Entity\Rating;
use App\Form\GamesType;
use App\Repository\GamesRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Promos;
use App\Repository\PromosRepository;
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
    public function list(Request $request, PaginatorInterface $paginator)
    {
        $donnees = $this->getDoctrine()->getRepository(games:: class)->findAll();
        $articles = $paginator->paginate($donnees,$request->query->getInt('page', 1),4


        );
        return $this->render('games/index.html.twig', [
                'articles'=>$articles, 1]);



    }

    /**
     * @Route("/gameslistF",name="gameslistF")
     */

    public function listF()
    {
        $rating=$this->getDoctrine()->
        getRepository(Rating::class)->findAll();
        $promos=$this->getDoctrine()->
        getRepository(Promos::class)->findAll();
        $classroom= $this->getDoctrine()->
        getRepository(Games::class)->findAll();
        return $this->render("games/indexF.html.twig",
            array('games'=>$classroom,'promos'=>$promos,'rating'=>$rating));
    }
    /**
     * @Route("/showfav",name="showfav")
     */

    public function showfav()
    {
        $promos=$this->getDoctrine()->
        getRepository(Promos::class)->findAll();
        $classroom= $this->getDoctrine()->
        getRepository(Games::class)->findAll();
        return $this->render("games/indexF.html.twig",
            array('games'=>$classroom,'promos'=>$promos));
    }

    /**
     * @Route("/addgames",name="addgames")
     */
    public function add(Request$request ){
        $games= new Games();
        $form= $this->createForm(GamesType::class,$games);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() ){
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
        $games=$this->getDoctrine()->getRepository(Games::class)->find($id);
        $form= $this->createForm(gamesType::class,$games);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute("gameslist");
        }
        return $this->render("games/modify.html.twig",array("formgames"=>$form->createView()));
    }
    /**
     * @Route("/addtofav/{id}",name="addtofav")
     */
    public function addtofav(Games $games){
        $games->addFavori($this->getUser());


            $em = $this->getDoctrine()->getManager();
            $em->persist($games);
            $em->flush();
            return $this->redirectToRoute("gameslistF");

    }
    /**
     * @Route("/rmfav/{id}",name="rmfav")
     */
    public function rmfav(Games $games){
        $games->removeFavori($this->getUser());


        $em = $this->getDoctrine()->getManager();
        $em->persist($games);
        $em->flush();
        return $this->redirectToRoute("gameslistF");

    }

}
