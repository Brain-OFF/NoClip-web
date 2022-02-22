<?php

namespace App\Controller;

use App\Entity\Coach;
use App\Form\CoachType;
use App\Repository\CoachRepository;
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
    public function list()
    {
        $classrooms= $this->getDoctrine()->
        getRepository(Coach::class)->findAll();
        return $this->render("coach/index.html.twig",
            array('tabcoach'=>$classrooms));
    }

    /**
     * @Route("/addcoach",name="addcoach")
     */
    public function add(Request$request ){
        $coach= new Coach();
        $form= $this->createForm(CoachType::class,$coach);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($coach);
            $em->flush();
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
    public function coachlistfront()
    {
        $coach= $this->getDoctrine()->
        getRepository(Coach::class)->findAll();
        return $this->render("coach/indexfront.html.twig",
            array('tabcoach'=>$coach));
    }



}
