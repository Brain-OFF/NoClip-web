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
     * @Route("/homer", name="homer")
     */
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
        /**
         * @Route("/home", name="home")
         */
        public function home(Request $request ){

            return $this->render("home/index.html.twig");
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
            if($form->isSubmitted() && $form->isValid()){
                $em = $this->getDoctrine()->getManager();
                $em->persist($Client);
                $em->flush();
                return $this->redirectToPath("tournoi");
            }
            return $this->render("Client/signup.html.twig",array("formSignup"=>$form->createView()));
        }
    }




