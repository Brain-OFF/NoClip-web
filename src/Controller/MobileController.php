<?php

namespace App\Controller;

use App\Entity\inscriptionT;
use App\Entity\Tournoi;
use App\Form\TournoiType;
use App\Repository\InscriptionTRepository;
use App\Repository\TournoiRepository;
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
    }
    /**
     * @Route("/updatemobile",name="updatemobile")
     */
    public function update(Request $request,NormalizerInterface $normalizer){
        $id = $request->get("id");
        $T= $this->getDoctrine()->getRepository(Tournoi::class)->find($id);
        $T->setNom($request->get("nom"));
        $T->setDiscription($request->get("discription"));
        $T->setCathegorie($request->get("cathegorie"));
        $T->setDate(new \DateTime($request->get("date")));
        $em = $this->getDoctrine()->getManager();
        $em->flush();
        $jsonContent = $normalizer->normalize($T,'json',['groups'=>'post:read']);

        return new Response(json_encode($jsonContent));

    }
    /**
     * @Route("/deleteT", name="deleteT")
     */
    public function delT(Request $request,NormalizerInterface $normalizer):Response
    {
        $id = $request->get("id");
        $em= $this->getDoctrine()->getManager();
        $T=$em->getRepository(Tournoi::class)->find($id);
        $em->remove($T);
        $em->flush();
        $jsonContent = $normalizer->normalize($T,'json',['groups'=>'post:read']);
        return new Response("User deleted Successfully ".json_encode($jsonContent));
    }

    /**
     * @Route("/createIns",name="createIns")
     */
    public function add_ins(Request $request,NormalizerInterface $normalizer)
    {
        $T = new inscriptionT();
        $id = $request->get("tournoi");
        $TT= $this->getDoctrine()->getRepository(Tournoi::class)->find($id);
        $T->setUserName($request->get("user_name"));
        $T->setEmail($request->get("email"));
        $T->setEtat($request->get("etat"));
        $T->setRank($request->get("Rank"));

        $T->setTournoi($TT);

        $em = $this->getDoctrine()->getManager();
        $em->persist($T);
        $em->flush();
        $jsonContent = $normalizer->normalize($T,'json',['groups'=>'read']);
        return new Response(json_encode($jsonContent));
    }

    /**
     * @Route("/listINSByTourM",name="listINSByTourM")
     */
    public function listINSByTour(Request $request,NormalizerInterface $normalizer) :Response
    {
        $id = $request->get("id");
        $inc = $this->getDoctrine()->getRepository(inscriptionT::class)->listInscriptionByTournoi($id);
        $jsonContent = $normalizer->normalize($inc,'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));
    }
    /**
     * @Route("/listINS",name="listINS")
     */
    public function listins(NormalizerInterface $normalizer): Response{
        $repository=$this->getDoctrine()->getRepository(inscriptionT::class);
        $tournois=$repository->findAll();
        $jsonContent = $normalizer->normalize($tournois,'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));
    }

    /**
     * @Route("/deleteins", name="deleteins")
     */
    public function delins(Request $request,NormalizerInterface $normalizer):Response
    {
        $id = $request->get("id");
        $em= $this->getDoctrine()->getManager();
        $T=$em->getRepository(inscriptionT::class)->find($id);
        $em->remove($T);
        $em->flush();
        $jsonContent = $normalizer->normalize($T,'json',['groups'=>'post:read']);
        return new Response("User deleted Successfully ".json_encode($jsonContent));
    }

    /**
     * @Route("/updateins12",name="updateins12")
     */
    public function updateins(Request $request,NormalizerInterface $normalizer){
        $id = $request->get("id");
        $T= $this->getDoctrine()->getRepository(inscriptionT::class)->find($id);
        $T->setUserName($request->get("user_name"));
        $T->setEmail($request->get("email"));
        $T->setRank($request->get("Rank"));

        $em = $this->getDoctrine()->getManager();
        $em->flush();
        $jsonContent = $normalizer->normalize($T,'json',['groups'=>'post:read']);

        return new Response(json_encode($jsonContent));

    }
    /**
     * @Route("/TreeM", name="TreeM")
     */
    public function TriM(NormalizerInterface $normalizer): Response
    {
        $repository=$this->getDoctrine()->getRepository(Tournoi::class);
        $tournois=$repository->orderByDate();
        $jsonContent = $normalizer->normalize($tournois,'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));
    }
    /**
     * @Route("/TreeM1", name="TreeM1")
     */
    public function TriMI(NormalizerInterface $normalizer): Response
    {
        $repository=$this->getDoctrine()->getRepository(Tournoi::class);
        $tournois=$repository->orderByDate();
        $jsonContent = $normalizer->normalize($tournois,'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));
    }

    /**
     * @Route("/searchM", name="search")
     */
    public function search(NormalizerInterface $normalizer,Request $request,TournoiRepository $rep): Response
    {        $nom = $request->get("nom");

        $repository=$this->getDoctrine()->getRepository(Tournoi::class);
        $tournois=$rep->searchbyname($nom);
        $jsonContent = $normalizer->normalize($tournois,'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));
    }

}
