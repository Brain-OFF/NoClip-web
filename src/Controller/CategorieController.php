<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @Route("/categorie")
 */
class CategorieController extends AbstractController
{
    /**
     * @Route("/", name="categorie_index", methods={"GET"})
     */
    public function index(CategorieRepository $categorieRepository): Response
    {
        return $this->render('categorie/index.html.twig', [
            'categories' => $categorieRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="categorie_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($categorie);
            $entityManager->flush();

            return $this->redirectToRoute('categorie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('categorie/new.html.twig', [
            'categorie' => $categorie,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/categorie_show", name="categorie_show", methods={"GET"})
     */




    public function show(Request $request)
    {
        $categorie= $this->getDoctrine()->
        getRepository(categorie::class)->find($request->get("id"));
        return $this->render("categorie/show.html.twig",
            array('categorie'=>$categorie));
    }





    /**
     * @Route("/{id}/edit", name="categorie_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Categorie $categorie, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('categorie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('categorie/edit.html.twig', [
            'categorie' => $categorie,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="categorie_delete", methods={"POST"})
     */
    public function delete(Request $request, Categorie $categorie, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categorie->getId(), $request->request->get('_token'))) {
            $entityManager->remove($categorie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('categorie_index', [], Response::HTTP_SEE_OTHER);
    }
    /**
     * @Route("/updatemobilecategorie",name="updatemobile")
     */
    public function update(Request $request,NormalizerInterface $normalizer)
    {
        $id = $request->get("id");
        $T = $this->getDoctrine()->getRepository(Categorie::class)->find($id);
        $T->setTitre($request->get("nom"));

        $em = $this->getDoctrine()->getManager();
        $em->flush();
        $jsonContent = $normalizer->normalize($T, 'json', ['groups' => 'post:read']);

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
     * @Route("/listcategoriemobile", name="listcategoriesmobile")
     */
    public function getallcategorie(NormalizerInterface $normalizer): Response
    {
        $repository=$this->getDoctrine()->getRepository(Categorie::class);
        $tournois=$repository->findAll();
        $jsonContent = $normalizer->normalize($tournois,'json',['groups'=>'read']);
        return new Response(json_encode($jsonContent));
    }
}
