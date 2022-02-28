<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\CoachRepository;
use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;

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
     * @Route("/reservationlisttrie",name="reservationlisttrie")
     */
    public function listtrie()
    {
        $reservation= $this->getDoctrine()->
        getRepository(Reservation::class)->orderByDate();
        return $this->render("reservation/indextrie.html.twig",
            array('tabeservation'=>$reservation));
    }
    /**
     * @Route("/addreservation",name="addreservation")
     */
    public function add(Request$request ){
        $reservation= new Reservation();
        $form= $this->createForm(ReservationType::class,$reservation);
        $form->handleRequest($request);
        $id = $form->getData($id);
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

    /**
     * @Route("/reservationlistfronttrie",name="reservationlistfronttrie")
     */
    public function listfronttrie()
    {
        $reservation= $this->getDoctrine()->
        getRepository(Reservation::class)->orderByDate();
        return $this->render("reservation/indexfronttrie.html.twig",
            array('tabeservation'=>$reservation));
    }

    /**
     * @Route("/addreservationfront",name="addreservationfront")
     */
    public function addfront(Request$request ){
        $reservation= new Reservation();
        $form= $this->createForm(ReservationType::class,$reservation);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()   ){
            $em = $this->getDoctrine()->getManager();
            $em->persist($reservation);
            $em->flush();
            return $this->redirectToRoute("reservationlistfront");
        }
        return $this->render("reservation/addfront.html.twig",array("formReservation"=>$form->createView()));
    }


    /**
     * @Route("/listreservationdecoach/{id}",name="listreservationdecoach")
     */
    public function listreservationdecoach($id)
    {
        $inc= $this->getDoctrine()->getRepository(Reservation::class)->listReservationByidtrier($id);
        return $this->render("coach/listreservation.html.twig",array('inc'=>$inc));
    }



    /**
     * @Route("/showpdf",name="showpdf")
     */
    public function showpdf(ReservationRepository $R)
    {

        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        $T= $R->findAll();
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('reservation/pdf.html.twig',
            ['tabINS'=>$T,]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => true
        ]);

    }

}
