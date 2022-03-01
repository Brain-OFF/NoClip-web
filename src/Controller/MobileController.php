<?php

namespace App\Controller;

use App\Entity\Tournoi;
use App\Form\TournoiType;
use Doctrine\DBAL\Types\DateTimeType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class MobileController extends AbstractController
{
    /**
     * @Route("/mobile", name="mobile")
     */
    public function index(): Response
    {
        return $this->render('mobile/index.html.twig', [
            'controller_name' => 'MobileController',
        ]);
    }
    /**
         * @Route("/getalltournois", name="getalltournois")
     */
    public function getalltournois(NormalizerInterface $normalizer): Response
    {
        $repository=$this->getDoctrine()->getRepository(Tournoi::class);
        $tournois=$repository->findAll();
        $jsonContent = $normalizer->normalize($tournois,'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));
    }
    /**
         * @Route("/createT",name="createT")
     */
    public function add(Request $request,NormalizerInterface $normalizer)
    {
        $T = new Tournoi();
        $T->setNom($request->get("nom"));
        $T->setDiscription($request->get("discription"));
        $T->setCathegorie($request->get("cathegorie"));
        $T->setDate(new \DateTime($request->get("date")));

        $em = $this->getDoctrine()->getManager();
        $em->persist($T);
        $em->flush();
        $jsonContent = $normalizer->normalize($T,'json',['groups'=>'read']);
        return new Response(json_encode($jsonContent));
    }}
