<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Coach;
use App\Entity\Commande;
use App\Entity\Games;
use App\Entity\Gamescat;
use App\Entity\inscriptionT;
use App\Entity\News;
use App\Entity\Reservation;
use App\Entity\Tournoi;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\UpdateUserType;
use App\Repository\GamesRepository;
use App\Repository\NewsRepository;
use App\Repository\TournoiRepository;
use App\Security\EmailVerifier as EmailVerifierAlias;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Normalizer\NormalizableInterface;
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
    public function loginmobile(Request $request,NormalizerInterface $normalizer,UserPasswordEncoderInterface $userPasswordEncoder):Response
    {
        $user=new User();
        $Un = $request->get('username');
        $pwd = $request->get('password');
        $repository=$this->getDoctrine()->getRepository(User::class);
        $user=$repository->findOneBy(['username' => $Un]);
        if (!$user)
        {
            $user1=new User();
            $user1->setUsername($Un);
            $user1->setPassword("x");
            $user1->setId(0);
            $user1->setEmail("x");
            $user1->setBio("x");
            $user1->setPoints(0);
            $jsonContent = $normalizer->normalize($user1,'json',['groups'=>'post:read']);
            return new Response(json_encode($jsonContent));
        }
        if($userPasswordEncoder->isPasswordValid($user,$pwd))
        {
            $jsonContent = $normalizer->normalize($user,'json',['groups'=>'post:read']);
            return new Response(json_encode($jsonContent));
        }
        else
        {
            $user1=new User();
            $user1->setUsername($Un);
            $user1->setPassword("x");
            $user1->setId(0);
            $user1->setEmail("x");
            $user1->setBio("x");
            $user1->setPoints(0);
            $jsonContent = $normalizer->normalize($user1,'json',['groups'=>'post:read']);
            return new Response(json_encode($jsonContent));
        }

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


    /**
     * @Route("/updateusermobile/", name="updateusermobile")
     */
    public function update(Request $request,UserPasswordEncoderInterface $userPasswordEncoder,NormalizerInterface $normalizer){
        $id=($request->get("id"));
        $user= $this->getDoctrine()->getRepository(User::class)->find($id);
        $user->setPoints($request->get("points"));
        $user->setBio($request->get("bio"));
        $user->setEmail($request->get("email"));
        $user->setUsername($request->get("username"));
            $user->setPassword(
                $userPasswordEncoder->encodePassword(
                    $user,
                    $request->get("password")));
            $em = $this->getDoctrine()->getManager();
            $em->flush();
        $jsonContent = $normalizer->normalize($user,'json',['groups'=>'post:read']);

        return new Response(json_encode($jsonContent));

    }


    /**
     * @Route("/deusersmobile", name="delusersmobile")
     */
     public function deleteusermobile(Request $request,NormalizerInterface $normalizer):Response
     {
        $repository=$this->getDoctrine()->getManager();
        $id=$request->get("id");
        $user=$repository->getRepository(User::class)->find($id);
        $repository->remove($user);
        $repository->flush();
        $jsonContent = $normalizer->normalize($user,'json',['groups'=>'post:read']);
        return new Response(json_encode("User deleted Successfully "));
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
    public function updatemobile(Request $request,NormalizerInterface $normalizer){
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
    public function createIns(Request $request,NormalizerInterface $normalizer)
    {
        $T = new inscriptionT();
        $id = $request->get("tournoi");
        $iduser=$request->get("user");
        $TT= $this->getDoctrine()->getRepository(Tournoi::class)->find($id);
        $user= $this->getDoctrine()->getRepository(User::class)->find($iduser);
        $T->setUserName($request->get("user_name"));
        $T->setEmail($request->get("email"));
        $T->setEtat($request->get("etat"));
        $T->setRank($request->get("Rank"));
        $T->setUser($user);
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
     * @Route("/getgamessmobilecat", name="getgamessmobilecat")
     */
    public function getgamesbycat(Request $request,NormalizerInterface $normalizer):Response
    {
        $cat=$this->getDoctrine()->getRepository(Gamescat::class)->find($request->get("cat"));

        $repository=$this->getDoctrine()->getRepository(Games::class)->listgamesbycat($cat);

        //$User=$repository->findAll();
        $jsonContent = $normalizer->normalize($repository,'json',['groups'=>'post:read']);
        //return $this->render('mobile/loginjson.html.twig',['data'=>$jsonContent]);
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
    public function searchM(NormalizerInterface $normalizer,Request $request,TournoiRepository $rep): Response
    {        $nom = $request->get("nom");

        $repository=$this->getDoctrine()->getRepository(Tournoi::class);
        $tournois=$rep->searchbyname($nom);
        $jsonContent = $normalizer->normalize($tournois,'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));
    }
    /**
     * @Route("/listcoachmobile", name="listcoachmobile")
     */

    public function getCoach(Request $request,NormalizerInterface $normalizer ):Response
    {
        $repository=$this->getDoctrine()->getRepository(Coach::class);
        $coachs=$repository->findAll();
        $jsonContent = $normalizer->normalize($coachs,'json',['groups'=>'coach']);
        return new Response(json_encode($jsonContent));
    }

    /**
     * @Route("/addcoachmobile", name="addcoachmobile")
     */

    public function addcoach(Request $request, NormalizerInterface $normalizer)
    {

        $coach = new Coach();
        $coach->setName($request->get("name"));
        $coach->setLastname($request->get("lastname"));
        $coach->setRank($request->get("rank"));
        $coach->setCategorie($request->get("categorie"));

        $em = $this->getDoctrine()->getManager();
        $em->persist($coach);
        $em->flush();

        $jsonContent = $normalizer->normalize($coach,'json',['groups'=>'coach']);
        return new Response("coach added Successfully ".json_encode($jsonContent));
    }

    /**
     * @Route("/updatecoachmobile",name="updatecoachmobile")
     */
    public function updatecoach(Request $request,NormalizerInterface $normalizer)
    {
        $id = $request->get("id");
        $coach = $this->getDoctrine()->getRepository(Coach::class)->find($id);
        $coach->setName($request->get("name"));
        $coach->setLastname($request->get("lastname"));
        $coach->setRank($request->get("rank"));
        $coach->setCategorie($request->get("categorie"));
        $em = $this->getDoctrine()->getManager();
        $em->flush();
        $jsonContent = $normalizer->normalize($coach, 'json', ['groups' => 'coach']);

        return new Response("coach updated Successfully ".json_encode($jsonContent));
    }


    /**
     * @Route("/deletecoachmobile/{id}", name="deletecoachmobile")
     */
    public function delcoach(Request $request,NormalizerInterface $normalizer,$id):Response
    {


        $em= $this->getDoctrine()->getManager();
        $coach=$em->getRepository(Coach::class)->find($id);
        $em->remove($coach);
        $em->flush();
        $jsonContent = $normalizer->normalize($coach,'json',['groups'=>'coach']);
        return new Response("coach deleted Successfully ".json_encode($jsonContent));
    }



    /**
     * @Route("/listreservationmobile", name="listreservationmobile")
     */

    public function getReservation(Request $request,NormalizerInterface $normalizer  ):Response
    {
        $repository=$this->getDoctrine()->getRepository(Reservation::class);
        $Reservation=$repository->findAll();
        $jsonContent = $normalizer->normalize($Reservation,'json',['groups'=>'reservation']);
        return new Response(json_encode($jsonContent));
    }

    /**
     * @Route("/addreservationmobile", name="addreservationmobile")
     */

    public function addreservation(Request $request, NormalizerInterface $normalizer)
    {

        $reservation = new Reservation();
        $id = $request->get("coach");
        $CC= $this->getDoctrine()->getRepository(Coach::class)->find($id);


        $reservation->setTempsstart(new \DateTime($request->get("start")));
        $reservation->setTempsend(new \DateTime($request->get("end")));
        $reservation->setDispo($request->get("dispo"));
        $reservation->setUser($this->getDoctrine()->getRepository(User::class)->find($request->get("id_user")));
        $reservation->setCoach($CC);

        $em = $this->getDoctrine()->getManager();
        $em->persist($reservation);
        $em->flush();

        $jsonContent = $normalizer->normalize($reservation,'json',['groups'=>'reservation']);
        return new Response("Reservation added Successfully ".json_encode($jsonContent));
    }

    /**
     * @Route("/updatereservationmobile",name="updatereservationmobile")
     */
    public function updatereservation(Request $request,NormalizerInterface $normalizer)
    {
        $id = $request->get("id");
        $idC = $request->get("coach");
        $CC= $this->getDoctrine()->getRepository(Coach::class)->find($idC);
        $reservation = $this->getDoctrine()->getRepository(Reservation::class)->find($id);
        $reservation->setTempsstart(new \DateTime($request->get("start")));
        $reservation->setTempsend(new \DateTime($request->get("end")));
        $reservation->setDispo($request->get("dispo"));
        $reservation->setCoach($CC);
        $em = $this->getDoctrine()->getManager();
        $em->flush();
        $jsonContent = $normalizer->normalize($reservation, 'json', ['groups' => 'reservation']);

        return new Response("Reservation updated Successfully ".json_encode($jsonContent));
    }


    /**
     * @Route("/deletereservationmobile/{id}", name="deletereservationmobile")
     */
    public function delreservation(Request $request,NormalizerInterface $normalizer,$id):Response
    {


        $em= $this->getDoctrine()->getManager();
        $reservation=$em->getRepository(Reservation::class)->find($id);
        $em->remove($reservation);
        $em->flush();
        $jsonContent = $normalizer->normalize($reservation,'json',['groups'=>'reservation']);
        return new Response("Reservation deleted Successfully ".json_encode($jsonContent));
    }
    /**
     * @Route("/getgamessmobile", name="getgamessmobile")
     */
    public function getgamessmobile(Request $request,NormalizerInterface $normalizer):Response
    {
        $repository=$this->getDoctrine()->getRepository(Games::class);
        $Games=$repository->findAll();
        //$User=$repository->findAll();
        $jsonContent = $normalizer->normalize($Games,'json',['groups'=>'post:read']);
        //return $this->render('mobile/loginjson.html.twig',['data'=>$jsonContent]);
        return new Response(json_encode($jsonContent));
    }
    /**
     * @Route("/getgamessmobilecat", name="getgamessmobilecat")
     */
    public function getgamessmobilecat(Request $request,NormalizerInterface $normalizer):Response
    {
        $cat=$this->getDoctrine()->getRepository(Gamescat::class)->find($request->get("cat"));

        $gamm=$this->getDoctrine()->getRepository(Games::class)->listgamebycat($cat);

        //$User=$repository->findAll();
        $jsonContent = $normalizer->normalize($gamm,'json',['groups'=>'post:read']);
        //return $this->render('mobile/loginjson.html.twig',['data'=>$jsonContent]);
        return new Response(json_encode($jsonContent));
    }

    /**
     * @Route("/getgamesscatmobile", name="getgamesscatmobile")
     */
    public function getgamesscatmobile(Request $request,NormalizerInterface $normalizer):Response
    {
        $repository=$this->getDoctrine()->getRepository(Gamescat::class);
        $Games=$repository->findAll();
        //$User=$repository->findAll();
        $jsonContent = $normalizer->normalize($Games,'json',['groups'=>'post:read']);
        //return $this->render('mobile/loginjson.html.twig',['data'=>$jsonContent]);
        return new Response(json_encode($jsonContent));
    }


    /**
     * @Route("/addgamescatm",name="addgamescatm")
     */
    public function addgamescatm(Request$request,NormalizerInterface $normalizer ){
        $Games= $this->getDoctrine()->getManager();
        $Games = new Gamescat();

        $Games->setNom($request->get("nom"));
        $Games->setDescription($request->get("description"));



        $em = $this->getDoctrine()->getManager();
        $em->persist($Games);
        $em->flush();

        $jsonContent = $normalizer->normalize($Games,'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));
    }



    /**
     * @Route("/addgamesm",name="addgamesm")
     */
    public function addgamesm(Request$request,NormalizerInterface $normalizer ){
        $Games= $this->getDoctrine()->getManager();
        $Games = new Games();

        $Games->setName($request->get("name"));
        $Games->setDescreption($request->get("desc"));
        $Games->setPrix($request->get("prix"));
        $Games->setImg($request->get("img"));
        $idcat=$request->get("cat");
        $cat=$this->getDoctrine()->getRepository(Gamescat::class)->find($idcat);
        $Games->addCat($cat);

        $em = $this->getDoctrine()->getManager();
        $em->persist($Games);
        $em->flush();

        $jsonContent = $normalizer->normalize($Games,'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));
    }
    /**
     * @Route("/updategamesmobile", name="updategamesmobile")
     */
    public function updategamesmobile(Request $request,NormalizerInterface $normalizer){
        $id=($request->get("id"));
        $user= $this->getDoctrine()->getRepository(Games::class)->find($id);
        $user->setName($request->get("name"));
        $user->setDescreption($request->get("desc"));
        $user->setPrix($request->get("prix"));
        $user->setImg($request->get("img"));

        $em = $this->getDoctrine()->getManager();
        $em->flush();
        $jsonContent = $normalizer->normalize($user,'json',['groups'=>'post:read']);

        return new Response(json_encode($jsonContent));

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
    /**
     * @Route("/addtofavmboile",name="addtofavmboile")
     */
    public function addtofavmboile(Request $request,NormalizerInterface $normalizer){
        $games=$this->getDoctrine()->getRepository(Games::class)->find($request->get("game"));
        $u=$request->get("user");


            $games->addFavorielse($this->getDoctrine()->getRepository(User::class)->find($request->get("user")));
            $em = $this->getDoctrine()->getManager();
            $em->persist($games);
            $em->flush();

        $jsonContent = $normalizer->normalize($games,'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));

    }
    /**
     * @Route("/getgamessmobilefav", name="getgamessmobilefav")
     */
    public function getgamessmobilefav(Request $request,NormalizerInterface $normalizer):Response
    {
        $games=array();
        $repository=$this->getDoctrine()->getRepository(Games::class)->findAll();
        foreach($repository as $game)
        {
            if ($game->verify_fav($this->getDoctrine()->getRepository(User::class)->find($request->get("user"))))
            {
                array_push($games,$game);
            }
        }
        //$User=$repository->findAll();
        $jsonContent = $normalizer->normalize($games,'json',['groups'=>'post:read']);
        //return $this->render('mobile/loginjson.html.twig',['data'=>$jsonContent]);
        return new Response(json_encode($jsonContent));
    }
    /**
     * @Route("/panier/add_jason/{id}", name="panier_add_jason")
     */
    public function add_jasonpanier($id, SessionInterface $session,NormalizerInterface $normalizer){
        $panier = $session->get('panier',[]);
        $jasonContent = $normalizer->normalize($panier,'json',['groups'=>'post:read']);
        if(!empty(($panier[$id]))) {
            $panier[$id]++;
        }  else {  $panier[$id] = 1;}

        $session->set('panier', $panier);



        return new Response(json_encode($jasonContent));}

    /**
     * @Route("/panierf_jason", name="panier_index_jason")
     */
    public function panierf_jason(SessionInterface $session, GamesRepository $productRepository,NormalizerInterface $normalizer){
        $panier = $session->get('panier', []);
        $jasonContent = $normalizer->normalize($panier,'json',['groups'=>'post:read']);
        $panierInfo = [];

        $total = 0;
        Foreach ($panier as $id => $quantite) {
            $equipement = $productRepository->find($id);
            $panierInfo[] = [


                'Equipement' => $productRepository->find($id),
                'quantite' => $quantite
            ];
            $total += $equipement->getPrix() * $quantite;
        }




        return new Response(json_encode($jasonContent));}

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
