<?php

namespace App\Controller;

use App\Entity\Coach;
use App\Entity\Reservation;
use App\Repository\CoachRepository;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\DocBlock\Serializer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;


class MobilecoachreservationController extends AbstractController
{
    /**
     * @Route("/mobilecoachreservation", name="mobilecoachreservation")
     */
    public function index(): Response
    {
        return $this->render('mobilecoachreservation/index.html.twig', [
            'controller_name' => 'MobilecoachreservationController',
        ]);
    }
    /**
     * @Route("/listcoachmobile", name="listcoachmobile")
     */

    public function getCoach(Request $request,NormalizerInterface $normalizer ):Response
    {
        $repository=$this->getDoctrine()->getRepository(Coach::class);
        $coachs=$repository->findAll();
        $jsonContent = $normalizer->normalize($coachs,'json',['groups'=>'coach']);
        return new Response(json_encode($jsonContent));
    }

    /**
     * @Route("/addcoachmobile", name="addcoachmobile")
     */

    public function addcoach(Request $request, NormalizerInterface $normalizer)
    {

        $coach = new Coach();
        $coach->setName($request->get("name"));
        $coach->setLastname($request->get("lastname"));
        $coach->setRank($request->get("rank"));
        $coach->setCategorie($request->get("categorie"));

        $em = $this->getDoctrine()->getManager();
        $em->persist($coach);
        $em->flush();

        $jsonContent = $normalizer->normalize($coach,'json',['groups'=>'coach']);
        return new Response("coach added Successfully ".json_encode($jsonContent));
    }

    /**
     * @Route("/updatecoachmobile",name="updatecoachmobile")
     */
    public function updatecoach(Request $request,NormalizerInterface $normalizer)
    {
        $id = $request->get("id");
        $coach = $this->getDoctrine()->getRepository(Coach::class)->find($id);
        $coach->setName($request->get("name"));
        $coach->setLastname($request->get("lastname"));
        $coach->setRank($request->get("rank"));
        $coach->setCategorie($request->get("categorie"));
        $em = $this->getDoctrine()->getManager();
        $em->flush();
        $jsonContent = $normalizer->normalize($coach, 'json', ['groups' => 'coach']);

        return new Response("coach updated Successfully ".json_encode($jsonContent));
    }


    /**
     * @Route("/deletecoachmobile/{id}", name="deletecoachmobile")
     */
    public function delcoach(Request $request,NormalizerInterface $normalizer,$id):Response
    {


        $em= $this->getDoctrine()->getManager();
        $coach=$em->getRepository(Coach::class)->find($id);
        $em->remove($coach);
        $em->flush();
        $jsonContent = $normalizer->normalize($coach,'json',['groups'=>'coach']);
        return new Response("coach deleted Successfully ".json_encode($jsonContent));
    }



    /**
     * @Route("/listreservationmobile", name="listreservationmobile")
     */

    public function getReservation(Request $request,NormalizerInterface $normalizer  ):Response
    {
        $repository=$this->getDoctrine()->getRepository(Reservation::class);
        $Reservation=$repository->findAll();
        $jsonContent = $normalizer->normalize($Reservation,'json',['groups'=>'reservation']);
        return new Response(json_encode($jsonContent));
    }

    /**
     * @Route("/addreservationmobile", name="addreservationmobile")
     */

    public function addreservation(Request $request, NormalizerInterface $normalizer)
    {

        $reservation = new Reservation();
        $id = $request->get("coach");
        $CC= $this->getDoctrine()->getRepository(Coach::class)->find($id);


        $reservation->setTempsstart(new \DateTime($request->get("start")));
        $reservation->setTempsend(new \DateTime($request->get("end")));
        $reservation->setDispo($request->get("dispo"));

        $reservation->setCoach($CC);

        $em = $this->getDoctrine()->getManager();
        $em->persist($reservation);
        $em->flush();

        $jsonContent = $normalizer->normalize($reservation,'json',['groups'=>'reservation']);
        return new Response("Reservation added Successfully ".json_encode($jsonContent));
    }

    /**
     * @Route("/updatereservationmobile",name="updatereservationmobile")
     */
    public function updatereservation(Request $request,NormalizerInterface $normalizer)
    {
        $id = $request->get("id");
        $idC = $request->get("coach");
        $CC= $this->getDoctrine()->getRepository(Coach::class)->find($idC);
        $reservation = $this->getDoctrine()->getRepository(Reservation::class)->find($id);
        $reservation->setTempsstart(new \DateTime($request->get("start")));
        $reservation->setTempsend(new \DateTime($request->get("end")));
        $reservation->setDispo($request->get("dispo"));
        $reservation->setCoach($CC);
        $em = $this->getDoctrine()->getManager();
        $em->flush();
        $jsonContent = $normalizer->normalize($reservation, 'json', ['groups' => 'reservation']);

        return new Response("Reservation updated Successfully ".json_encode($jsonContent));
    }


    /**
     * @Route("/deletereservationmobile/{id}", name="deletereservationmobile")
     */
    public function delreservation(Request $request,NormalizerInterface $normalizer,$id):Response
    {


        $em= $this->getDoctrine()->getManager();
        $reservation=$em->getRepository(Reservation::class)->find($id);
        $em->remove($reservation);
        $em->flush();
        $jsonContent = $normalizer->normalize($reservation,'json',['groups'=>'reservation']);
        return new Response("Reservation deleted Successfully ".json_encode($jsonContent));
    }

}