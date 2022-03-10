<?php

namespace App\Controller;

use App\Entity\Images;
use App\Entity\Like;
use App\Entity\News;
use App\Form\NewsType;
use App\Repository\NewsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Annotations\DocLexer;

/**
 * @Route("/news")
 */
class NewsController extends AbstractController
{
    /**
     * @Route("/news_index", name="news_index", methods={"GET"})
     */
    public function index(NewsRepository $newsRepository): Response
    {
        return $this->render('news/index.html.twig', [
            'news' => $newsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="news_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $news = new News();
        $form = $this->createForm(NewsType::class, $news);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $images = $form->get('images')->getData();
            // Boucle sur les images

            foreach ($images as $image) {


          $fichier = md5(uniqid()) . '.' . $image->guessExtension();
           //On copie le fichier dans le dossier upload
            $image->move(
            $this->getParameter('upload_directory'),$fichier

                 );
            // on stocke l'image dans la bdd (son nom)
               $img = new Images();
                $img->setName($fichier);
                $news->addImage($img);
                               }
            $entityManager->persist($news);
            $entityManager->persist($img);
            $entityManager->flush();

            return $this->redirectToRoute('news_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('news/new.html.twig', [
            'news' => $news,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/news_show", name="news_show", )
     */
    public function show()
    {
        $news= $this->getDoctrine()->
        getRepository(news::class)->findAll();

        return $this->render("news/show.html.twig",
            ['news'=>$news,'liked'=>$this->getDoctrine()->
            getRepository(Like::class)->findAll()]);
    }
    /**
     * @Route("/like/{id}", name="like", )
     */
    public function like($id)
    {
        $article=$this->getDoctrine()->getRepository(news::class)->find($id);
        $Like= $this->getDoctrine()->getRepository(Like::class)->search_like($this->getUser(),$article);
        if (!$Like)
        {
            $L=new Like();
            $L->setUser($this->getUser());
            $L->setArticle($article);
            $L->setStatus("L");
            $em = $this->getDoctrine()->getManager();
            $em->persist($L);
            $em->flush();
        }
        else
        {
            if ($Like->getStatus()=="L")
            {
                $em = $this->getDoctrine()->getManager();
                $em->remove($Like);
                $em->flush();
            }
            else
            {
                $Like->setStatus("L");
                $em = $this->getDoctrine()->getManager();
                $em->flush();
            }
        }
        return $this->redirectToRoute('news_show');
    }
    /**
     * @Route("/dislike/{id}", name="dislike", )
     */
    public function dislike($id)
    {
        $article=$this->getDoctrine()->getRepository(news::class)->find($id);
        $Like= $this->getDoctrine()->getRepository(Like::class)->search_like($this->getUser(),$article);
        if (!$Like)
        {
            $L=new Like();
            $L->setUser($this->getUser());
            $L->setArticle($article);
            $L->setStatus("D");
            $em = $this->getDoctrine()->getManager();
            $em->persist($L);
            $em->flush();
        }
        else
        {
            if ($Like->getStatus()=="D")
            {
                $em = $this->getDoctrine()->getManager();
                $em->remove($Like);
                $em->flush();
            }
            else
            {
                $Like->setStatus("D");
                $em = $this->getDoctrine()->getManager();
                $em->flush();
            }
        }
        return $this->redirectToRoute('news_show');
    }


    /**
     * @Route("/{id}/edit", name="news_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, News $news, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(NewsType::class, $news);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('news_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('news/edit.html.twig', [
            'news' => $news,
            'form' => $form->createView(),
        ]);
    }
//
    /**
     * @Route("deleteuser/{id}", name="news_delete")
     */
      public function delete($id){
           $news= $this->getDoctrine()->getRepository(News::class)->find($id);
          $em= $this->getDoctrine()->getManager();
          $em->remove($news);
         $em->flush();
          return $this->redirectToRoute("news_show");
    }

    /**
     * @Route("/TrierParDateDESC", name="TrierParDateDESC")
     */
    public function TrierParDate(Request $request): Response
    {
        $repository = $this->getDoctrine()->getRepository(News::class);
        $new = $repository->findByDate();

        return $this->render('news/index.html.twig', [
            'news' => $new,
        ]);
    }








}


