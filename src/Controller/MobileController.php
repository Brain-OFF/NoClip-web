<?php

namespace App\Controller;

use App\Entity\Commande;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

use Symfony\Component\Mime\Address;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


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
     * @Route("/getcommandesmobile", name="getcommandesmobile")
     */
    public function getcommandes(Request $request,NormalizerInterface $normalizer):Response
    {
        $repository=$this->getDoctrine()->getRepository(Commande::class);
        $commandes=$repository->findAll();
        //$User=$repository->findAll();
        $jsonContent = $normalizer->normalize($commandes,'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));
    }
    /**
     * @Route("/addcostmobile", name="addcostmobile")
     */
    public function addCommande(Request $request, NormalizerInterface $normalizer)
    {
        $user = new Commande();
        $user->setNom($request->get("nom"));
        $user->setPrenom($request->get("prenom"));
        $user->setEmail($request->get("email"));
        $user->setAdresse($request->get("adresse"));
        $user->setNumtelephone($request->get("numtelephone"));
        $user->setTotalcost($request->get("totalcost"));
        $d=$request->get("dateCommande");
        $user->setDateCommande(new \DateTime($d));
        $em=$this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        $jsonContent = $normalizer->normalize($user,'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));
    }


}
