<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\News;
use App\Repository\NewsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizableInterface;
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
     * @Route("/listnewsmobile", name="listnewsmobile")
     */

    public function listenews(Request $request,NormalizableInterface  $normalizer , NewsRepository  $C ):Response
    {
        $repository=$this->getDoctrine()->getRepository(News::class);
        $new=$repository->findAll();
        $jsonContent = $normalizer->normalize($new,'jason',['groups'=>'read']);
        return new Response(json_encode($jsonContent));
    }

    /**
     * @Route("/addnewsmobile", name="addnewsmobile")
     */

    public function addnews(Request   $request, NormalizableInterface  $normalizer)
    {
        $em = $this->getDoctrine()->getManager();

        $new = new News();
        $new->setTitre($request->get("Titre"));
        $new->setText($request->get("Text"));
        $new->setjeu($request->get("jeu"));
        $new->setCategorie($request->get("categorie"));

        $em = $this->getDoctrine()->getManager();
        $em->persist($new);
        $em->flush();

        $jsonContent = $normalizer->normalize($new,'json',['groups'=>'new']);
        return new Response(json_encode($jsonContent));
    }


    /**
     * @Route("/addcategorie",name="addcateg")
     */
    public function categorienew(Request $request,NormalizerInterface $normalizer)
    {
        $em = $this->getDoctrine()->getManager();


        $equipement = new Categorie();

        $equipement->setNom($request->get('nom'));



        $em->persist($equipement);
        $em->flush();
        $jasonContent = $normalizer->normalize($equipement,'jason',['groups'=>'read']);
        return new Response(json_encode($jasonContent));

    }



}
