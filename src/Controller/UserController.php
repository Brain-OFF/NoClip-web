<?php

namespace App\Controller;
use App\Entity\Client;
use App\Entity\Promos;
use App\Entity\User;
use App\Form\SignupType;
use App\Form\UpdateUserType;
use App\Repository\GamesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
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
        $Users= $this->getDoctrine()->
        getRepository(User::class)->findAll();
        return $this->render("Client/index.html.twig",
            array('users'=>$Users));
    }
    /**
     * @Route("/deleteuser/{id}",name="deleteuser")
     */
    public function delete($id){
        $user= $this->getDoctrine()->getRepository(User::class)->find($id);
        $em= $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();
        return $this->redirectToRoute("/user");
    }

    /**
     * @Route("/updateuser/{id}",name="updateuser")
     */
    public function update(Request $request,$id,UserPasswordEncoderInterface $userPasswordEncoder){
        $user= $this->getDoctrine()->getRepository(User::class)->find($id);
        $form= $this->createForm(UpdateUserType::class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $user->setPassword(
                $userPasswordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute("users");
        }
        return $this->render("client/modify.html.twig",array("formUser"=>$form->createView()));
    }

    /**
     * @Route("/listgamesbyuser/{id}", name="listgamesbyuser")
     */
    public function listgamesbyuser(GamesRepository   $repository,$id)
    {
        $games=$repository->listgamebyuser($id);
        $promos= $this->getDoctrine()->
        getRepository(Promos::class)->findAll();
        return $this->render("games/indexF.html.twig",array("games"=>$games,'promos'=>$promos));
    }
}




