<?php

namespace App\Controller;



use App\Entity\Gamescat;

use App\Entity\Promos;
use App\Form\GamesCatType;
use App\Repository\GamescatRepository;
use App\Repository\GamesRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GamescatController extends AbstractController
{
    /**
     * @Route("/gamescat", name="gamescat")
     */
    public function index(): Response
    {

        return $this->render('gamescat/index.html.twig', [
            'controller_name' => 'GamescatController'
        ]);
    }
    /**
     * @Route("/gamescatF", name="gamescatF")
     */
    public function index2(): Response
    {

        return $this->render('gamescat/indexF.html.twig', [
            'controller_name' => 'GamescatController'
        ]);
    }



    /**
     * @Route("/gamescatlist",name="gamescatlist")
     */
    public function list(Request $request, PaginatorInterface $paginator)
    {
        $donnees = $this->getDoctrine()->getRepository(Gamescat :: class)->findAll();
        $articles = $paginator->paginate($donnees,$request->query->getInt('page', 1),4


        );
        return $this->render('gamescat/index.html.twig', [
                'articles'=>$articles, 1]);


    }

    /**
     * @Route("/gamescatlistF",name="gamescatlistF")
     */

    public function listF()
    {


        $classroom= $this->getDoctrine()->
        getRepository(Gamescat::class)->findAll();
        return $this->render("gamescat/indexF.html.twig",
            array('gamescat'=>$classroom));
    }

    /**
     * @Route("/addgamescat",name="addgamescat")
     */
    public function add(Request$request ){
        $gamescat= new gamescat();
        $form= $this->createForm(GamesCatType::class,$gamescat);

            $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() ){
            $em = $this->getDoctrine()->getManager();
            $em->persist($gamescat);
            $em->flush();
            return $this->redirectToRoute("gamescatlist");
        }
        return $this->render("gamescat/add.html.twig",array("formgamescat"=>$form->createView()));
    }
    /**
     * @Route("/removegamescat/{id}",name="removegamescat")
     */
    public function delete($id){
        $gamescat= $this->getDoctrine()->getRepository(Gamescat::class)->find($id);
        $em= $this->getDoctrine()->getManager();
        $em->remove($gamescat);
        $em->flush();
        return $this->redirectToRoute("gamescatlist");
    }
    /**
     * @Route("/modifygamescat/{id}",name="modifygamescat")
     */
    public function update(Request $request,$id){
        $gamescat= $this->getDoctrine()->getRepository(Gamescat::class)->find($id);
        $form= $this->createForm(GamesCatType::class,$gamescat);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid() ){
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute("gamescatlist");
        }
        return $this->render("gamescat/modify.html.twig",array("formgamescatmodif"=>$form->createView()));
    }
    /**
     * @Route("/listgamesbycat/{id}", name="listgamesbycat")
     */
    public function listgamesbycat(GamesRepository   $repository,$id)
    {
        $games=$repository->listgamebycat($id);
        $promos= $this->getDoctrine()->
        getRepository(Promos::class)->findAll();
        return $this->render("games/indexF.html.twig",array("games"=>$games,'promos'=>$promos));
    }

}
