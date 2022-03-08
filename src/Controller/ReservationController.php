<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationfrontType;
use App\Form\ReservationType;
use App\Repository\CalendarRepository;
use App\Repository\CoachRepository;
use App\Repository\ReservationRepository;
use Knp\Component\Pager\PaginatorInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
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
     * @Route("/fullcalendar", name="fullcalendar")
     */
    public function reservationback(ReservationRepository $calendar)
    {
        $rdvs = [];
        $events = $calendar->findAll();
        foreach ($events as $event)
        {
            $rdvs[] = [
                'id' => $event->getId(),
                'title' => $event->getCoach()->__toString(),
                'start' => $event->getTempsstart()->format('Y-m-d H:i:s'),
                'end' => $event->getTempsend()->format('Y-m-d H:i:s'),
            ];
        }
        $data = json_encode($rdvs);
        return $this->render('reservation/fullcalendrier.html.twig', compact('data'));
    }

    /**
     * @Route("/reservationlist",name="reservationlist")
     */
    public function list(PaginatorInterface $paginator , Request $request )
    {
        $reservation= $paginator->paginate($this->getDoctrine()->getRepository(Reservation::class)->findAllVisibleQuery()
        , $request->query->getInt('page', 1),4

        );
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
    public function add(Request$request , FlashyNotifier $flash ){
        $reservation= new Reservation();
        $form= $this->createForm(ReservationType::class,$reservation);
        $form->handleRequest($request);
        $id = $form->getData();
        if($form->isSubmitted() && $form->isValid()   ){
            $em = $this->getDoctrine()->getManager();
            $em->persist($reservation);
            $em->flush();
            $this->addFlash('info','added successfully!');
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
        $this->addFlash('info2','deleted successfully!');
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
            $this->addFlash('info3','modify successfully!');
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
    public function listfront(PaginatorInterface $paginator , Request $request)
    {
        $reservation= $paginator->paginate($this->getDoctrine()->getRepository(Reservation::class)->findAllVisibleQuerybydispo()
            , $request->query->getInt('page', 1),12

        );
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
        $form= $this->createForm(ReservationfrontType::class,$reservation);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()   ){
            $em = $this->getDoctrine()->getManager();
            $em->persist($reservation);
            $em->flush();

            $this->addFlash(
                'info',
                'added successfully!'
            );
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
