<?php

namespace App\Controller;

use App\Entity\inscriptionT;
use App\Entity\Tournoi;
use App\Form\SearchTournoiType;
use App\Form\TournoiType;
use App\Repository\InscriptionTRepository;
use App\Repository\TournoiRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;


class TournoiController extends AbstractController
{

    /**
     * @Route("/tournoi", name="tournoi")
     */
    public function index(): Response
    {
        return $this->render('tournoi/index.html.twig', [
            'controller_name' => 'TournoiController',
        ]);
    }

    /**
     * @Route("/listINSByTour/{id}",name="listINSByTour")
     */
    public function listINSByTour($id)
    {
        $inc = $this->getDoctrine()->getRepository(inscriptionT::class)->listInscriptionByTournoi($id);
        return $this->render("tournoi/listINSByTour.html.twig", array('inc' => $inc));
    }

    /**
     * @Route("/TT", name="TT")
     */
    public function T(): Response
    {
        return $this->render('tournoi/tournaments.html.twig', [
            'controller_name' => 'TournoiController',
        ]);
    }

    /**
     * @Route("/addT",name="addT")
     */
    public function add(Request $request)
    {
        $T = new Tournoi();
        $form = $this->createForm(TournoiType::class, $T);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($T);
            $em->flush();
            return $this->redirectToRoute("showT");
        }
        return $this->render("tournoi/AddTT.html.twig", array("formT" => $form->createView()));
    }

    /**
     * @Route("/showT",name="showT")
     */
    public function show(TournoiRepository $T, Request $request , PaginatorInterface $paginator)
    {
        $donnes = $T->findAll();
        $Tor = $paginator->paginate(
            $donnes,
            $request->query->getInt('page', 1),
            4
        );
        $formSearch = $this->createForm(SearchTournoiType::class);
        $formSearch->handleRequest($request);
        if ($formSearch->isSubmitted()) {
            $nom = $formSearch->getData();
            $TSearch = $T->searchCathegorie($nom);
            return $this->render("tournoi/searchcath.html.twig", array("cath" => $TSearch, 'tabT' => $Tor, "formSearch" => $formSearch->createView()));
        }
        return $this->render("tournoi/AffichT.html.twig", array('tabT' => $Tor, "formSearch" => $formSearch->createView()));;
    }


    /**
     * @Route("/showTr",name="showTr")
     */
    public function showTr(TournoiRepository $T)
    {
        $TriDate = $T->orderByDate();
        return $this->render("tournoi/AffichTri.html.twig", array("TriDate" => $TriDate));
    }


    /**
     * @Route("/remove/{id}",name="removeT")
     */
    public function delete($id)
    {
        $T = $this->getDoctrine()->getRepository(Tournoi::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($T);
        $em->flush();
        return $this->redirectToRoute("showT");
    }

    /**
     * @Route("/update/{id}",name="update")
     */
    public function update(Request $request, $id)
    {
        $TT = $this->getDoctrine()->getRepository(Tournoi::class)->find($id);
        $form = $this->createForm(TournoiType::class, $TT);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute("showT");
        }
        return $this->render("tournoi/update.html.twig", array("formT" => $form->createView()));
    }

    /**
     * @Route("/showTT",name="showTT")
     */
    public function showf(Request $request, TournoiRepository $T)
    {
        $list = $T->findAll();
        $formSearch = $this->createForm(SearchTournoiType::class);
        $formSearch->handleRequest($request);
        if ($formSearch->isSubmitted()) {
            $nom = $formSearch->getData();
            $TSearch = $T->searchCathegorie($nom);
            return $this->render("tournoi/searchcath2.html.twig", array("cath" => $TSearch, 'tabT' => $list, "formSearch" => $formSearch->createView()));
        }
        return $this->render("tournoi/AffichTT.html.twig", array('tabT' => $list, "formSearch" => $formSearch->createView()));;
    }


}