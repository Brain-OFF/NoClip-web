<?php

namespace App\Controller;

use App\Entity\Coach;
use App\Form\CoachType;
use App\Form\SearchType;
use App\Repository\CoachRepository;
use Knp\Component\Pager\PaginatorInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CoachController extends AbstractController
{

    /* back functions*/

    /**
     * @Route("/coachback", name="coachback")
     */
    public function indexback(): Response
    {

        return $this->render('coach/index.html.twig', [
            'controller_name' => 'CoachController'
        ]);
    }

    /**
     * @Route("/coachlist",name="coachlist")
     */
    public function list(CoachRepository $C, Request $request ,PaginatorInterface $paginator )
    {
        $list= $paginator->paginate($C->findAll(), $request->query->getInt('page', 1),4) ;
        $formSearch=$this->createForm(SearchType::class);
        $formSearch->handleRequest($request);
        if($formSearch->isSubmitted() ){
           $name = $formSearch->getData();
           $TSearch = $paginator->paginate($C->searchCathegorie($name), $request->query->getInt('page', 1),4);
            return $this->render("coach/indexsearch.html.twig", array('tabcoach'=>$list , "cath"=>$TSearch , "formSearch"=>$formSearch->createView()));
        }
        return $this->render("coach/index.html.twig", array('tabcoach'=>$list, "formSearch"=>$formSearch->createView()));
    }

    /**
     * @Route("/addcoach",name="addcoach")
     */
    public function add(Request$request ,  FlashyNotifier $flash ){
        $coach= new Coach();
        $form= $this->createForm(CoachType::class,$coach);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($coach);
            $em->flush();
            $this->addFlash('info','added successfully!');
            return $this->redirectToRoute("coachlist");
        }
        return $this->render("coach/add.html.twig",array("formCoach"=>$form->createView()));
    }

    /**
     * @Route("/removecoach/{id}",name="removecoach")
     */
    public function delete($id){
        $coach= $this->getDoctrine()->getRepository(Coach::class)->find($id);
        $em= $this->getDoctrine()->getManager();
        $em->remove($coach);
        $em->flush();
        $this->addFlash('info2','deleted successfully!');
        return $this->redirectToRoute("coachlist");
    }
    /**
     * @Route("/modifycoach/{id}",name="modifycoach")
     */
    public function update(Request $request,$id){
        $coach= $this->getDoctrine()->getRepository(Coach::class)->find($id);
        $form= $this->createForm(CoachType::class,$coach);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('info3','modified successfully!');
            return $this->redirectToRoute("coachlist");
        }
        return $this->render("coach/modify.html.twig",array("formCoach"=>$form->createView()));
    }





    /* front functions*/

    /**
     * @Route("/coach", name="coach")
     */
    public function index(): Response
    {

        return $this->render('coach/index.html.twig', [
            'controller_name' => 'CoachController'
        ]);
    }
    /**
     * @Route("/coachlistfront",name="coachlistfront")
     */
    public function coachlistfront(CoachRepository $C, Request $request , PaginatorInterface $paginator  )
    {
        $coach= $paginator->paginate($C->findAll(), $request->query->getInt('page', 1),4) ;
        $formSearch=$this->createForm(SearchType::class);
        $formSearch->handleRequest($request);
        if($formSearch->isSubmitted() ){
            $name = $formSearch->getData();
            $TSearch = $paginator->paginate($C->searchCathegorie($name), $request->query->getInt('page', 1),4) ;
            return $this->render("coach/indexsearchfront.html.twig", array('tabcoach'=>$coach , "cath"=>$TSearch , "formSearch"=>$formSearch->createView()));
        }
        return $this->render("coach/indexfront.html.twig",
            array('tabcoach'=>$coach , "formSearch"=>$formSearch->createView() ));
    }



}
