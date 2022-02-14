<?php

namespace App\Controller;
use App\Entity\Client;
use App\Form\SignupType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
    /**
     * @Route("/signup", name="signup")
     */
    public function Singup(Request $request ){
        $Client= new Client();
        $form= $this->createForm(SignupType::class,$Client);
        $Client->setPhoto("img/default_pic.png");
        $Client->setPoints(0);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($Client);
            $em->flush();
            return $this->redirectToPath("tournoi");
        }
        return $this->render("Client/Signup.html.twig",array("formSignup"=>$form->createView()));
    }


}
