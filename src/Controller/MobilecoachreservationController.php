<?php

namespace App\Controller;

use App\Entity\Coach;
use App\Repository\CoachRepository;
use ContainerAto38xC\EntityManager_9a5be93;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\DocBlock\Serializer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;


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
    public  function getCoach(CoachRepository $C , SerializerInterface $serializerInterface)
    {
        $coach=$C->findAll();
        $json = $serializerInterface->serialize($coach,'json',['groups'=>'coach']);
        dump($json) ;
        die;
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
        return new Response(json_encode($jsonContent));
    }



    /**
     * @Route("/updatecoachmobile/{id}",name="updatecoachmobile")
     */
    public function update(Request $request,$id,NormalizerInterface $normalizer){
        $coach= $this->getDoctrine()->getRepository(Coach::class)->find($id);
        $coach->setName($request->get("name"));
        $coach->setLastname($request->get("lastname"));
        $coach->setRank($request->get("rank"));
        $coach->setCategorie($request->get("categorie"));

        $em = $this->getDoctrine()->getManager();
        $em->flush();
        $jsonContent = $normalizer->normalize($coach,'json',['groups'=>'coach']);

        return new Response(json_encode($jsonContent));

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
        return new Response("User deleted Successfully ".json_encode($jsonContent));
    }





}
