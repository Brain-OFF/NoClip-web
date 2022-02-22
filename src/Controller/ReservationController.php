<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ReservationController extends AbstractController
{
// back template

    /**
     * @Route("/reservationback", name="reservationback")
     */
    public function reservationback(): Response
    {
        return $this->render('reservation/index.html.twig', [
            'controller_name' => 'ReservationController',
        ]);
    }

    /**
     * @Route("/reservationlist",name="reservationlist")
     */
    public function list()
    {
        $reservation= $this->getDoctrine()->
        getRepository(Reservation::class)->findAll();
        return $this->render("reservation/index.html.twig",
            array('tabeservation'=>$reservation));
    }

    /**
     * @Route("/addreservation",name="addreservation")
     */
    public function add(Request$request ){
        $reservation= new Reservation();
        $form= $this->createForm(ReservationType::class,$reservation);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()   ){
            $em = $this->getDoctrine()->getManager();
            $em->persist($reservation);
            $em->flush();
            return $this->redirectToRoute("reservationlist");
        }
        return $this->render("reservation/add.html.twig",array("formReservation"=>$form->createView()));
    }
    /**
     * @Route("/removereservation/{id}",name="removereservation")
     */
    public function delete($id){
        $reservation= $this->getDoctrine()->getRepository(Reservation::class)->find($id);
        $em= $this->getDoctrine()->getManager();
        $em->remove($reservation);
        $em->flush();
        return $this->redirectToRoute("reservationlist");
    }
    /**
     * @Route("/modifyreservation/{id}",name="modifyreservation")
     */
    public function update(Request $request,$id){
        $reservation= $this->getDoctrine()->getRepository(Reservation::class)->find($id);
        $form= $this->createForm(ReservationType::class,$reservation);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute("reservationlist");
        }
        return $this->render("reservation/modify.html.twig",array("formReservation"=>$form->createView()));
    }

// front template
    /**
     * @Route("/reservation", name="reservation")
     */
    public function index(): Response
    {
        return $this->render('reservation/index.html.twig', [
            'controller_name' => 'ReservationController',
        ]);
    }

    /**
     * @Route("/reservationlistfront",name="reservationlistfront")
     */
    public function listfront()
    {
        $reservation= $this->getDoctrine()->
        getRepository(Reservation::class)->findAll();
        return $this->render("reservation/indexfront.html.twig",
            array('tabeservation'=>$reservation));
    }
}
