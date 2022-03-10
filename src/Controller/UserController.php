<?php

namespace App\Controller;
use App\Entity\Client;
use App\Entity\Promos;
use App\Entity\User;
use App\Form\EmailPassForgottenType;
use App\Form\ModifyuserType;
use App\Form\PassType;
use App\Form\SignupType;
use App\Form\UpdateUserType;
use App\Repository\GamesRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
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
     * @Route("/profile", name="Profile")
     */
    public function profile()
    {
        if (!$this->getUser())
            return $this->redirectToRoute("home");
        $Users= $this->getDoctrine()->
        getRepository(User::class)->find($this->getUser()->getId());
        return $this->render("user/profile.html.twig",
            array('users'=>$Users));
    }
    /**
     * @Route("/editprofile", name="editprofile")
     */
    public function profileedit(Request $request,UserPasswordEncoderInterface $userPasswordEncoder)
    {
        if (!$this->getUser())
            return $this->redirectToRoute("home");
        $user= $this->getDoctrine()->getRepository(User::class)->findbyusername($this->getUser()->getUsername());
        $user->setImage("");
        $form= $this->createForm(ModifyuserType::class,$user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $user->setPassword(
                $userPasswordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $image= $form['image']->getData();
            try {
                if(!is_dir("images_users")){
                    mkdir("images_users");
                }
                $filename=$image->getFileName();
                move_uploaded_file($image,"images_users/".$image->getFileName());

                rename("images_users/".$image->getFileName() , "images_users/".$user->getId().$user->getUsername().".".$image->getClientOriginalExtension());

            }
            catch (IOExceptionInterface $e) {
                echo "Erreur Profil existant ou erreur upload image ".$e->getPath();
            }
            $user->setImage("images_users/".$user->getId().$user->getUsername().".".$image->getClientOriginalExtension ());
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute("Profile");
        }
        return $this->render("user/editprofile.html.twig",array("formUser"=>$form->createView()));
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
        $user->setImage("");
        $form= $this->createForm(UpdateUserType::class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $user->setPassword(
                $userPasswordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $image= $form['image']->getData();
            try {
                if(!is_dir("images_users")){
                    mkdir("images_users");
                }
                $filename=$image->getFileName();
                move_uploaded_file($image,"images_users/".$image->getFileName());

                rename("images_users/".$image->getFileName() , "images_users/".$user->getId().$user->getUsername().".".$image->getClientOriginalExtension());

            }
            catch (IOExceptionInterface $e) {
                echo "Erreur Profil existant ou erreur upload image ".$e->getPath();
            }
            $user->setImage("images_users/".$user->getId().$user->getUsername().".".$image->getClientOriginalExtension ());
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute("users");
        }
        return $this->render("client/modify.html.twig",array("formUser"=>$form->createView()));
    }
        /**
        *@Route("/forgotten_pass", name="app_forgotten_password")
         */
         public function forgottenPass(Request $request, UserRepository $userRepo, MailerInterface $mailer, TokenGeneratorInterface $tokenGenerator) {
             if ($this->getUser()) {
                 return $this->redirectToRoute('home');
             }
        $form = $this->createForm(EmailPassForgottenType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $donnees =$form->get('email')->getData();
            $user=$this->getDoctrine()->getRepository(User::class)->findOneByEmail($donnees);
            if(!$user){
                $this->addFlash('danger','cettte adresse n\exsite pas');
                return  $this->render('user/passforgotten.html.twig', ['form' => $form->createView(),'Message'=>"Entrez Votre Email!"]);}
            $token= $tokenGenerator->generateToken();
            try {
                $user->setResetToken($token);
                $entityManager =$this->getDoctrine()->getManager();
                $entityManager->flush();
            }catch (\Exception $e){
                $this->addFlash('warning', 'une erreur est servenue : '. $e->getMessage());
                return  $this->render('user/passforgotten.html.twig', ['form' => $form->createView(),'Message'=>"Entrez Votre Email!"]);
            }
            $url = $this->generateUrl('app_reset_password', ['token' => $token]);
            $message = (new TemplatedEmail())
                ->from('svnoclip11@gmail.com')
                ->to($user->getEmail())
                ->html(
                    "<p> bonjour ,</p><p></p> une demande de reintilation de mot de passe a ete effectué pour le le site gamepad.fr.
                            veuillez cliquer sur le lien suivant: 127.0.0.1:8000" .$url ."</p>");

            $mailer->send($message);
            $this->addFlash('message' , "un e_mail de renitialisation de mot de passe  vous a ete envoye");
            return $this->redirectToRoute('app_login');}
        return  $this->render('user/passforgotten.html.twig', ['form' => $form->createView(),'Message'=>"Entrez Votre Email!"]);
    }
    /**
     * @Route ("/reset_pass/{token}" , name="app_reset_password")
     */
    public function resetpass(Request $request,$token,UserPasswordEncoderInterface $userPasswordEncoder,EntityManagerInterface $entityManager ){
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }
        $user=$this->getDoctrine()->getManager()->getRepository(User::class)->findytoken($token);
        if (!$user)
        {
            return $this->redirectToRoute("login");
        }
        $form = $this->createForm(PassType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted())
        {
            $user->setPassword(
                $userPasswordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setResetToken(null);
            $entityManager->flush();
            return $this->redirectToRoute("app_login");
        }
        return $this->render ('user/passforgotten.html.twig', ['form' => $form->createView(),'Message'=>"Entrez Votre Nouveau mot de passe!"]);

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




