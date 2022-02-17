<?php

namespace App\Controller;
use App\Entity\Client;
use App\Form\SignupType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;


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
                return $this->redirectToPath("home");
            }
            return $this->render("Client/signup.html.twig",array("formSignup"=>$form->createView()));
        }
    /**
     * @Route("/users", name="users")
     */
    public function list()
    {
        $Clients= $this->getDoctrine()->
        getRepository(Client::class)->findAll();
        return $this->render("Client/users.html.twig",
            array('clients'=>$Clients));
    }
    /**
     * @Route("/remove/{id}",name="removeuser")
     */
    public function delete($id){
        $user= $this->getDoctrine()->getRepository(Client::class)->find($id);
        $em= $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();
        return $this->redirectToRoute("users");
    }

}




