<?php

namespace OCSymfony\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdvertController extends Controller {

    public function deleteAction ($id) {

        return $this->render('OCSymfonyBundle:Advert:delete.html.twig');
    }

    public function editAction ($id, Request $request) {

        // Vérification si la requête est en POST
        if ($request->isMethod('POST')) {
            // Création du message flash de confirmation
            $request->getSession()->getFlashBag()->add('notice', 'L\'annonce à bien été modifiée');

            // Redirection vers la page de visualisation de cette annonce
            return $this->redirectToRoute('oc_platform_view', array('id' => 5));
        }
        // Si ce n'est pas POST
        return $this->render('OCSymfonyBundle:Advert:edit.html.twig');

    }

    public function addAction(Request $request) {

        // Vérification si la requête est en POST
        if ($request->isMethod('POST')) {
            // Création du message flash de confirmation
            $request->getSession()->getFlashBag()->add('notice', 'L\'annonce à bien été enregistrée');

            // Redirection vers la page de visualisation de cette annonce
            return $this->redirectToRoute('oc_platform_view', array('id' => 5));
        }
        // Si ce n'est pas POST
        return $this->render('OCSymfonyBundle:Advert:add.html.twig');

    }

    public function viewAction($id) {

        return $this->render('OCSymfonyBundle:Advert:view.html.twig', array('id' => $id));
    }

    Public function indexAction($page) {
        // Vérification si la page est inferieur à 1
        if ($page < 1) {
            // Affichage d'une page d'erreur 404
            throw new NotFoundHttpException('La page "'.$page.'" n\'existe pas.');
        }

        // Appel du template
        return $this->render('OCSymfonyBundle:Advert:view.html.twig');
    }
}