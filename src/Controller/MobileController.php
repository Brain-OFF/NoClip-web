<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\UpdateUserType;
use App\Security\EmailVerifier as EmailVerifierAlias;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;


class MobileController extends AbstractController
{
    private EmailVerifierAlias $emailVerifier;
    public function __construct(EmailVerifierAlias $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }
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
     * @Route("/loginmobile", name="loginmobile")
     */
    public function login(Request $request,NormalizerInterface $normalizer,UserPasswordEncoderInterface $userPasswordEncoder):Response
    {
        $user=new User();
        $Un = $request->get('u');
        $pwd = "walid123456";
        $repository=$this->getDoctrine()->getRepository(User::class);
        $user=$repository->findOneBy(['username' => $Un]);
        if($userPasswordEncoder->isPasswordValid($user,$pwd))
            echo "valid";
        else
            echo "hehehe";
        //$User=$repository->findAll();
        $jsonContent = $normalizer->normalize($user,'json',['groups'=>'post:read']);
        //return $this->render('mobile/loginjson.html.twig',['data'=>$jsonContent]);
        return new Response(json_encode($jsonContent));
    }

    /**
     * @Route("/getusermobile/{id}", name="getusersmobile")
     */
    public function getsingleuser(Request $request,NormalizerInterface  $normalizer,$id):Response
    {
        $repository=$this->getDoctrine()->getRepository(User::class);
        $users=$repository->find($id);
        $jsonContent = $normalizer->normalize($users,'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));
    }
    /**
     * @Route("/addusermobile", name="addusermobile")
     */
    public function adduser(Request $request, NormalizerInterface $normalizer,UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $user= $this->getDoctrine()->getManager();
        $user = new User();
        $user->setPoints(0);
        $user->setBio("");
        $user->setEmail($request->get("email"));
        $user->setUsername($request->get("username"));
        $user->setIsVerified(false);
            $user->setPassword(
                $userPasswordEncoder->encodePassword(
                    $user,
                    $request->get("password")
                )
            );
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
        $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
            (new TemplatedEmail())
                ->from(new Address('svnoclip11@gmail.com', 'sv_noclip'))
                ->to($user->getEmail())
                ->subject('Please Confirm your Email')
                ->htmlTemplate('registration/confirmation_email.html.twig')
        );
        $jsonContent = $normalizer->normalize($user,'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));
    }
    /**
     * @Route("/getusersmobile", name="getusersmobile")
     */
    public function getusers(Request $request,NormalizerInterface $normalizer):Response
    {
        $repository=$this->getDoctrine()->getRepository(User::class);
        $users=$repository->findAll();
        //$User=$repository->findAll();
        $jsonContent = $normalizer->normalize($users,'json',['groups'=>'post:read']);
        //return $this->render('mobile/loginjson.html.twig',['data'=>$jsonContent]);
        return new Response(json_encode($jsonContent));
    }

}
