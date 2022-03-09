<?php

namespace App\Controller;
use App\Entity\Games;
use App\Form\GamesType;
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
     * @Route("/getgamessmobile", name="getgamessmobile")
     */
    public function getgamess(Request $request,NormalizerInterface $normalizer):Response
    {
        $repository=$this->getDoctrine()->getRepository(Games::class);
        $Games=$repository->findAll();
        //$User=$repository->findAll();
        $jsonContent = $normalizer->normalize($Games,'json',['groups'=>'post:read']);
        //return $this->render('mobile/loginjson.html.twig',['data'=>$jsonContent]);
        return new Response(json_encode($jsonContent));
    }

    /**
     * @Route("/addgamesm",name="addgamesm")
     */
    public function add(Request$request,NormalizerInterface $normalizer ){
        $Games= $this->getDoctrine()->getManager();
        $Games = new Games();

        $Games->setName($request->get("name"));
        $Games->setDescreption($request->get("desc"));
        $Games->setPrix($request->get("prix"));
        $Games->setImg($request->get("img"));
        $Games->setIsVerified(false);

        $em = $this->getDoctrine()->getManager();
        $em->persist($Games);
        $em->flush();

        $jsonContent = $normalizer->normalize($Games,'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));
    }

}
