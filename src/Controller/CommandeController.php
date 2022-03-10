<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Form\CommandeType;
use App\Form\CommandeUPType;
use App\Form\SearchCommandeType;
use App\Repository\CommandeRepository;
use App\Repository\GamesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email ;
use Knp\Component\Pager\PaginatorInterface;
use Endroid\QrCodeBundle\Response\QrCodeResponse;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;

use Knp\Bundle\PaginatorBundle\Pagination\SlidingPaginationInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
class CommandeController extends AbstractController
{


    public function sendEmail(MailerInterface $mailer,String $mail)
    {
        $email = (new Email())
            ->from(new Address('svnoclip91@gmail.com', 'sv_noclip'))
            ->to($mail)
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('validation du commande')
            ->text('Sending emails is fun again!')
            ->html('<p>You command have been successfully passed !</p>');

        $mailer->send($email);

    }

    /**
     * @Route("/index", name="commande_index", methods={"GET"})
     */
    public function index(CommandeRepository $commandeRepository,Request $request): Response
    {

        return $this->render('commande/.html.twig', [
            'commandes' => $commandeRepository->findAll(),

        ]);
    }
    /**
     * @Route("/commandelist",name="commandelist")
     */


    public function list(CommandeRepository $commandeRepository,Request $request, PaginatorInterface $paginator)
    {

        $commande = new Commande();
        $formSearch = $this->createForm(SearchCommandeType::class);
        $formSearch->handleRequest($request);


        if ($formSearch->isSubmitted()){
            $term=$formSearch->getData();
            $allcommande=$commandeRepository->search($term);
            return $this->render("commande/search.html.twig",
                array("commandes"=>$allcommande,"formSearch"=>$formSearch->createView()));
        }

        $commande= $this->getDoctrine()->
        getRepository(Commande::class)->findAll();

        return $this->render("commande/index.html.twig",
            array("commandes"=>$commande,"formSearch"=>$formSearch->createView()));
    }


    /**
     * @Route("/newcommande", name="commande_new", methods={"GET","POST"})
     */
    public function new(Request $request,SessionInterface $session, GamesRepository  $gamesRepository, MailerInterface $mailer ): Response
    {
        $commande = new Commande();
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);
        $panier = $session->get('panier', []);

        $panierwithData = [];

        $total = 0;
        Foreach ($panier as $id => $quantity) {
            $product = $gamesRepository->find($id);
            $panierwithData[] = [


                'product' => $gamesRepository->find($id),
                'quantity' => $quantity
            ];
            $total += $product->getPrix() * $quantity;
        }
        $Quantite=[];
        if ($form->isSubmitted() && $form->isValid()) {
            for ($i = 0; $i < count($panierwithData ); $i++) {

                $commande->addListP($panierwithData [$i]['product']);
                $Quantite[$i]=($panierwithData [$i]['quantity']);



            }

            $this->addFlash('success', 'votre commande a été Ajouter !!');
            $commande->setDateCommande(new \DateTime());
            $commande->Quantite=$Quantite;

            $commande->setTotalcost($total);
            $commande->setUser($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($commande);
            $entityManager->flush();
            $this->sendEmail($mailer,$commande->getEmail());
            $panier = $session->get('panier', []);
            $session->clear();
            return $this->redirectToRoute('commandelist');
        }
        $writer = new PngWriter();
// Create QR code
        $qrCode = QrCode::create('commande')
            ->setEncoding(new Encoding('UTF-8'))
            ->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
            ->setSize(100)
            ->setMargin(5)
            ->setRoundBlockSizeMode(new RoundBlockSizeModeMargin())
            ->setForegroundColor(new Color(0, 0, 0))
            ->setBackgroundColor(new Color(255, 255, 255));

// Create generic logo
        $logo = Logo::create('C:\xampp\htdocs\NoClip-web - Copy\public\assets\logo.png')
            ->setResizeToWidth(50);

// Create generic label
        $label = Label::create('Label')
            ->setTextColor(new Color(255, 0, 0));

        $qr = $writer->write($qrCode, $logo, $label);


        return $this->render('commande/new.html.twig',
            ['commande' => $commande,'panierwithData' => $panierwithData,'total'=>$total,'qr'=>$qr,'form'=>$form->createView()]);
    }



    /**
     * @param Request $request
     * @param int $id
     * @param CommandeRepository $repository
     * @return Response
     * @Route ("/modifiercommandeback/{id}",name="modifiercommandeback")
     */
    public function modify(Request $request, int $id,CommandeRepository $repository): Response
    {
        //$entityManager = $this->getDoctrine()->getManager();
        $commande=$repository->find($id);
        //$classroom = $entityManager->getRepository(Classroom::class)->find($id);
        $form = $this->createForm(CommandeUPType::class, $commande);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->flush();

            return $this->redirectToRoute('commandelist');

        }

        return $this->render("commande/modify.html.twig", [
            "fo" => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}", name="commande_delete", methods={"POST"})
     */
    public function delete(Request $request, Commande $commande): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commande->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($commande);
            $entityManager->flush();
            $this->addFlash('success', ' Commande Anuuler !!');
        }

        return $this->redirectToRoute('commandelist');
    }
    /**
     * @Route("/TrierParDateDESC", name="TrierParDateDESC")
     */
    public function TrierParDate(Request $request): Response
    {
        $repository = $this->getDoctrine()->getRepository(Commande::class);
        $commande = $repository->findByDate();

        return $this->render('commande/index.html.twig', [
            'commandes' => $commande,
        ]);
    }

    /**
     * @Route("/showTT",name="showTT")
     */
    public function search(Request $request, CommandeRepository $T)
    {
        $list = $T->findAll();
        $formSearch = $this->createForm(SearchCommandeType::class);
        $formSearch->handleRequest($request);
        if ($formSearch->isSubmitted()) {
            $nom = $formSearch->getData();
            $commande = $T->search($nom);
            return $this->render("commande/search.html.twig", array("cath" => $commande, 'tabT' => $list, "formSearch" => $formSearch->createView()));
        }
        return $this->render("commande/index.html.twig",  array("tabT"=>$list,"formSearch"=>$formSearch->createView()));
    }

}
