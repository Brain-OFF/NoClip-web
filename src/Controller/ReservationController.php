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
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;




class ReservationController extends AbstractController
{
// back template

    /**
     * @Route("/fullcalendar", name="fullcalendar")
     */
    public function reservationback(ReservationRepository $calendar)
    {
        if ($this->getUser()->getStatus()!="admin")
            return $this->redirectToRoute("home");
        $rdvs = [];
        $events = $calendar->findAll();
        foreach ($events as $event)
        {

                $rdvs[] = [
                    'id' => $event->getId(),
                    'discription' => $event->getDispo(),
                    'title' => $event->getCoach()->__toString(),
                    'start' => $event->getTempsstart()->format('Y-m-d H:i:s'),
                    'end' => $event->getTempsend()->format('Y-m-d H:i:s'),
                ];

        }
        $data = json_encode($rdvs);
        return $this->render('reservation/fullcalendrier.html.twig', compact('data'));
    }
    /**
     * @Route("/fullcalendarfront", name="fullcalendarfront")
     */
    public function reservationfront(ReservationRepository $calendar)
    {
        if ($this->getUser()->getStatus()!="admin")
            return $this->redirectToRoute("home");
        $rdvs = [];
        $events = $calendar->findAll();
        foreach ($events as $event)
        {
            if($event->getDispo() == true)
            {
            $rdvs[] = [
                'id' => $event->getId(),
                'title' => $event->getCoach()->__toString(),
                'start' => $event->getTempsstart()->format('Y-m-d H:i:s'),
                'end' => $event->getTempsend()->format('Y-m-d H:i:s'),
            ];
            }
        }
        $data = json_encode($rdvs);
        return $this->render('reservation/fullcalendarfront.html.twig', compact('data'));
    }

    /**
     * @Route("/reservationlist",name="reservationlist")
     */
    public function list(PaginatorInterface $paginator , Request $request )
    {
        if ($this->getUser()->getStatus()!="admin")
            return $this->redirectToRoute("home");
        $reservation= $paginator->paginate($this->getDoctrine()->getRepository(Reservation::class)->findAllVisibleQuery()
        , $request->query->getInt('page', 1),4

        );
        return $this->render("reservation/index.html.twig",
            array('tabeservation'=>$reservation));
    }

    /**
     * @Route("/reservationlisttrie",name="reservationlisttrie")
     */
    public function listtrie(PaginatorInterface $paginator , Request $request)
    {
        if ($this->getUser()->getStatus()!="admin")
            return $this->redirectToRoute("home");
        $reservation= $paginator->paginate($this->getDoctrine()->
        getRepository(Reservation::class)->orderByDate() , $request->query->getInt('page', 1),4);
        return $this->render("reservation/indextrie.html.twig",
            array('tabeservation'=>$reservation));
    }
    /**
     * @Route("/addreservation",name="addreservation")
     */
    public function add(Request$request , FlashyNotifier $flash ){
        if ($this->getUser()->getStatus()!="admin")
            return $this->redirectToRoute("home");
        $reservation= new Reservation();
        $form= $this->createForm(ReservationType::class,$reservation);
        $form->handleRequest($request);
        $id = $form->getData();
        if($form->isSubmitted() && $form->isValid()   ){
            $reservation->setUser($this->getUser());
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
        if ($this->getUser()->getStatus()!="admin")
            return $this->redirectToRoute("home");
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
        if ($this->getUser()->getStatus()!="admin")
            return $this->redirectToRoute("home");
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
        $reservation= $paginator->paginate($this->getDoctrine()->getRepository(Reservation::class)->findAllVisibleQuerybydispo(), $request->query->getInt('page', 1),4

        );
        return $this->render("reservation/indexfront.html.twig",
            array('tabeservation'=>$reservation));
    }

    /**
     * @Route("/reservationlistfronttrie",name="reservationlistfronttrie")
     */
    public function listfronttrie(Request $request , PaginatorInterface $paginator)
    {
        $reservation=$paginator->paginate($this->getDoctrine()->getRepository(Reservation::class)->orderByDate(), $request->query->getInt('page', 1),4
        );
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

            $this->addFlash('info','added successfully!');
            return $this->redirectToRoute("reservationlistfront");
        }
        return $this->render("reservation/addfront.html.twig",array("formReservation"=>$form->createView()));
    }


    /**
     * @Route("/listreservationdecoach/{id}",name="listreservationdecoach")
     */
    public function listreservationdecoach($id , Request $request , PaginatorInterface $paginator)
    {
        $inc= $paginator->paginate($this->getDoctrine()->getRepository(Reservation::class)->listReservationByidtrier($id), $request->query->getInt('page', 1),4 );
        return $this->render("coach/listreservation.html.twig",array('inc'=>$inc));
    }



    /**
     * @Route("/showpdfReser",name="showpdfReser")
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
        $dompdf->stream("reservationPdf.pdf", [
            "Attachment" => true
        ]);


    }


    public function __construct( EntityManagerInterface $entityManager)
    {

        $this->entityManager = $entityManager;
    }

    private function getData(): array
    {
        /**
         * @var $user Reservation[]
         */
        $list = [];
        $users = $this->entityManager->getRepository(Reservation::class)->findAll();

        foreach ($users as $user) {
            $list[] = [
                $user->getId(),
                $user->getTempsstart(),
                $user->getTempsend(),
                $user->getDispo(),
                $user->getCoach()

            ];
        }
        return $list;
    }

    /**
     * @Route("/exportExcelReser",  name="exportExcelReser")
     */
    public function export()
    {
        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setTitle('User List');

        $sheet->getCell('A1')->setValue('id');
        $sheet->getCell('B1')->setValue('tempsstart');
        $sheet->getCell('C1')->setValue('tempsend');
        $sheet->getCell('C1')->setValue('dispo');
        $sheet->getCell('C2')->setValue('coach');


        // Increase row cursor after header write
        $sheet->fromArray($this->getData(),null, 'A2', true);


        $writer = new Xlsx($spreadsheet);

        $writer->save('reservations.xlsx');

        $this->addFlash('info4','excel file is in public !');
        return $this->redirectToRoute("reservationlist");
    }

}
