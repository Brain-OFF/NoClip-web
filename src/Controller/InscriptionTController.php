<?php

namespace App\Controller;

use App\Entity\inscriptionT;
use App\Entity\Tournoi;
use App\Form\InscriptionTType;
use App\Form\TournoiType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;



class InscriptionTController extends AbstractController
{
    /**
     * @Route("/inscription/t", name="inscription_t")
     */
    public function index(): Response
    {
        return $this->render('inscription_t/index.html.twig', [
            'controller_name' => 'InscriptionTController',
        ]);
    }
  /**
     * @Route("/addIns", name="addIns")
     */
    public function add(Request $request)
    {
        $ins = new inscriptionT();
        $form = $this->createForm(InscriptionTType::class, $ins);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ins);
            $em->flush();
            return $this->redirectToRoute("showTT");

        }
        return $this->render("inscription_t/addINC.html.twig", array("formINS" => $form->createView()));

    }
    /**
     * @Route("/showINS",name="showINC")
     */
    public function show()
    {
        $T= $this->getDoctrine()->
        getRepository(inscriptionT::class)->findAll();
        return $this->render("inscription_t/AffichINS.html.twig",
            array('tabINS'=>$T));
    }
    /**
     * @Route("/removeINC/{id}",name="removeINC")
     */
    public function delete($id){
        $INC= $this->getDoctrine()->getRepository(inscriptionT::class)->find($id);
        $em= $this->getDoctrine()->getManager();
        $em->remove($INC);
        $em->flush();
        return $this->redirectToRoute("showINC");
    }

    /**
     * @Route("/updateINC/{id}",name="updateINC")
     */
    public function update(Request $request,$id){
        $TT= $this->getDoctrine()->getRepository(inscriptionT::class)->find($id);
        $form= $this->createForm(InscriptionTType::class,$TT);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute("showINC");
        }
        return $this->render("Inscription_t/updateins.html.twig",array("formINS"=>$form->createView()));
    }




}