<?php

namespace App\Controller;
use App\Entity\Games;
use App\Entity\Gamescat;
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
     * @Route("/getgamessmobilecat", name="getgamessmobilecat")
     */
    public function getgamesbycat(Request $request,NormalizerInterface $normalizer):Response
    {
		$cat=$this->getDoctrine()->getRepository(Gamescat::class)->find($request->get("cat"));
		
        $gamm=$this->getDoctrine()->getRepository(Games::class)->listgamebycat($cat);
      
        //$User=$repository->findAll();
        $jsonContent = $normalizer->normalize($gamm,'json',['groups'=>'post:read']);
        //return $this->render('mobile/loginjson.html.twig',['data'=>$jsonContent]);
        return new Response(json_encode($jsonContent));
    }
	
	 /**
     * @Route("/getgamesscatmobile", name="getgamesscatmobile")
     */
    public function getgamesscat(Request $request,NormalizerInterface $normalizer):Response
    {
        $repository=$this->getDoctrine()->getRepository(Gamescat::class);
        $Games=$repository->findAll();
        //$User=$repository->findAll();
        $jsonContent = $normalizer->normalize($Games,'json',['groups'=>'post:read']);
        //return $this->render('mobile/loginjson.html.twig',['data'=>$jsonContent]);
        return new Response(json_encode($jsonContent));
    }
	
	
	/**
     * @Route("/addgamescatm",name="addgamescatm")
     */
    public function addcat(Request$request,NormalizerInterface $normalizer ){
        $Games= $this->getDoctrine()->getManager();
        $Games = new Gamescat();

        $Games->setNom($request->get("nom"));
        $Games->setDescription($request->get("description"));
        
      

        $em = $this->getDoctrine()->getManager();
        $em->persist($Games);
        $em->flush();

        $jsonContent = $normalizer->normalize($Games,'json',['groups'=>'post:read']);
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
        $idcat=$request->get("cat");
        $cat=$this->getDoctrine()->getRepository(Gamescat::class)->find($idcat);
        $Games->addCat($cat);

        $em = $this->getDoctrine()->getManager();
        $em->persist($Games);
        $em->flush();

        $jsonContent = $normalizer->normalize($Games,'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));
    }
	    /**
     * @Route("/updategamesmobile", name="updategamesmobile")
     */
    public function update(Request $request,NormalizerInterface $normalizer){
        $id=($request->get("id"));
        $user= $this->getDoctrine()->getRepository(Games::class)->find($id);
        $user->setName($request->get("name"));
        $user->setDescreption($request->get("desc"));
        $user->setPrix($request->get("prix"));
        $user->setImg($request->get("img"));
            
            $em = $this->getDoctrine()->getManager();
            $em->flush();
        $jsonContent = $normalizer->normalize($user,'json',['groups'=>'post:read']);

        return new Response(json_encode($jsonContent));

    }

}
