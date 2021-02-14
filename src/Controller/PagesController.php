<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Categorie;
use App\Repository\AnnonceRepository;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use cebe\markdown\Markdown;

class PagesController extends AbstractController
{
    /**
     * @Route("/")
     * @Route("/accueil", name="accueil")
     */
    public function index(): Response
    {
        $user = $this->getUser();

        return $this->render('pages/accueil.html.twig');
    }

    /**
     * @Route("/annonce/{categorie}/{option}", name="annonce")
     */
    public function annonceListing(CategorieRepository $repoC, AnnonceRepository $repo, Markdown $markdown, String $categorie, String $option)
    {
        if($categorie == 'all' and $option == 'all'){
            $annonces = $repo->findAll();
        }
        elseif($categorie == 'all'){
            $annonces = $repo->findBy([],array('prix'=>$option));
        }
        elseif($option == 'all'){
            $idCategorie = $repoC->findOneBy(array('nom'=>$categorie))->getId();
            $annonces = $repo->findBy(array('categorie'=>$idCategorie));
        }
        else{
            $idCategorie = $repoC->findOneBy(array('nom'=>$categorie))->getId();
            $annonces = $repo->findBy(array('categorie'=>$idCategorie),array('prix'=>$option));
        }

        $parsedAnnonces = [];
        foreach ($annonces  as $annonce) {
            $parseAnnonce = $annonce;
            $parseAnnonce ->setDescription($markdown->parse($annonce->getDescription()));
            $parsedAnnonces[] = $parseAnnonce;
        }
        return $this->render('pages/index.html.twig', [
            'categorie' => $categorie,
            'annonces' => $parsedAnnonces,
            'option' => $option
        ]);
    }


    /**
     * @Route("categorie", name="categorie")
     */
    public function categorieListing(CategorieRepository $repo)
    {
        $categories = $repo->findAll();

        return $this->render('pages/index.html.twig', [
            'categories' => $categories
        ]);
    }


    /**
     * @Route("/annonce/{id}/show", name="annonce_show")
     */
    public function show(Annonce $annonce, Markdown $markdown): Response
    {
        $parseAnnonce = $annonce;
        $parseAnnonce ->setDescription($markdown->parse($annonce->getDescription()));
        return $this->render('pages/show.html.twig', [
            'annonce' => $annonce
        ]);
    }

    /**
     * @Route("/create", name="annonce_create")
     */
    public function create(CategorieRepository $repo, Request $request): Response
    {
        $user = $this->getUser();
        if ($user == null) {
            return $this->redirectToRoute('app_login');
        }

        $categories = $repo->findAll();
        $annonce = new Annonce();
        $form = $this->createFormBuilder($annonce)
            ->add('nom')
            ->add('description')
            ->add('prix')
            ->add('categorie')
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $annonce->setAuteur($user);
            $entityManager->persist($annonce);
            $entityManager->flush();
            return $this->redirectToRoute('annonce_show', ['id' => $annonce->getId()]);
        }

        return $this->render('pages/create.html.twig', [
            'annonce' => $annonce,
            'categories' => $categories,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/createCategorie", name="categorie_create")
     */
    public function createCategorie(CategorieRepository $repo, Request $request): Response
    {
        $user = $this->getUser();
        if ($user == null) {
            return $this->redirectToRoute('app_login');
        }

        $categorie = new Categorie();
        $form = $this->createFormBuilder($categorie)
            ->add('nom')
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($categorie);
            $entityManager->flush();
            return $this->redirectToRoute('categorie');
        }

        return $this->render('pages/createCategorie.html.twig', [
            'categorie' => $categorie,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/annonce/{id}/delete", name="annonce_delete")
     */
    public function remove(Annonce $annonce): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($annonce);
        $entityManager->flush();

        return $this->redirectToRoute('annonce', array(
            'categorie' => 'all',
            'option' => 'all'
        ));
    }
    /*
    /**
     * @Route("/categorie/{id}/delete", name="categorie_delete")
     */
    /*
    public function removeCategorie(Categorie $categorie): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($categorie);
        $entityManager->flush();

        return $this->redirectToRoute('categorie');
    }
*/
    /**
     * @Route("/annonce/{id}/edit", name="annonce_edit")
     */
    public function edit(CategorieRepository $repo, Request $request,Annonce $annonce): Response
    {
        $categories = $repo->findAll();
        $form = $this->createFormBuilder($annonce)
            ->add('nom')
            ->add('description')
            ->add('categorie')
            ->add('prix')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('annonce', array(
                'categorie' => 'all',
                'option' => 'all'
            ));
        }

        return $this->render('pages/edit.html.twig', [
            'annonce' => $annonce,
            'categories' => $categories,
            'form' => $form->createView()
        ]);
    }
}
