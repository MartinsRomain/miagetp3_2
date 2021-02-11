<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Repository\AnnonceRepository;
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
        return $this->render('pages/accueil.html.twig');
    }

    /**
     * @Route("/annonce/{option}", name="annonce")
     */
    public function annonceListing(AnnonceRepository $repo, Markdown $markdown, String $option)
        {
            if($option == 'all'){
                $annonces = $repo->findAll();
            }
            else{
                $annonces = $repo->findBy([], array('prix'=>$option));
            }

            $parsedAnnonces = [];
            foreach ($annonces  as $annonce) {
                $parseAnnonce = $annonce;
                $parseAnnonce ->setDescription($markdown->parse($annonce->getDescription()));
                $parsedAnnonces[] = $parseAnnonce;
            }
            return $this->render('pages/index.html.twig', [
                'annonces' =>$parsedAnnonces
            ]);
    }

    /**
     * @Route("/annonce/show/{id}", name="annonce_show")
     */
    public function show(Annonce $annonce, Markdown $markdown): Response
    {
        $parseAnnonce = $annonce;
        $parseAnnonce ->setDescription($markdown->parse($annonce->getDescription()));
        return $this->render('pages/show.html.twig', [
            'annonce' =>$annonce
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
            'option' => 'all'
        ));
    }

    /**
     * @Route("/annonce/{id}/edit", name="annonce_edit")
     */
    public function edit(Request $request,Annonce $annonce): Response
    {
        $form = $this->createFormBuilder($annonce)
            ->add('nom')
            ->add('description')
            ->add('prix')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('annonce', array(
                'option' => 'all'
            ));
        }

        return $this->render('pages/edit.html.twig', [
            'annonce' => $annonce,
            'form' => $form->createView()
        ]);
    }
}
