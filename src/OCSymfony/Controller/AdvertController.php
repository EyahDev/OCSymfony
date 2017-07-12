<?php

namespace OCSymfony\Controller;

use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdvertController extends Controller {

    public function menuAction() {

        // liste du menu
        $listAdverts = array(
            array('id' => 2, 'title' => 'Recherche développeur Symfony'),
            array('id' => 5, 'title' => 'Mission de webmaster'),
            array('id' => 9, 'title' => 'Offre de stage webdesigner')
        );

        // Retourne le menu avec la liste
        return $this->render('OCSymfonyBundle:Advert:menu.html.twig', array('listAdverts' => $listAdverts));
    }

    public function deleteAction ($id) {

        return $this->render('OCSymfonyBundle:Advert:delete.html.twig');
    }

    public function editAction ($id, Request $request) {

        $advert = array(
            'title' => 'Recherche développeur Symfony2',
            'id' => $id,
            'author' => 'Alexandre',
            'content' => 'Nous rechercons un développeur Symfony2 pour débuytant sur Lyon. Bla bla...',
            'date' => new \DateTime()
        );

        // Vérification si la requête est en POST
        if ($request->isMethod('POST')) {
            // Création du message flash de confirmation
            $request->getSession()->getFlashBag()->add('notice', 'L\'annonce à bien été modifiée');

            // Redirection vers la page de visualisation de cette annonce
            return $this->redirectToRoute('oc_platform_view', array('id' => 5));
        }
        // Si ce n'est pas POST
        return $this->render('OCSymfonyBundle:Advert:edit.html.twig', array('advert' => $advert));

    }

    public function addAction(Request $request) {

        // Récupération du service
        $antispam = $this->container->get('oc_platform.antispam');

        $text = "....";

        if ($antispam->isSpam($text)) {
            throw new Exception('Votre message  été détecté comme spam');
        }

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

        $advert = array(
            'title' => 'Recherche développeur Symfony2',
            'id' => $id,
            'author' => 'Alexandre',
            'content' => 'Nous rechercons un développeur Symfony2 pour débuytant sur Lyon. Bla bla...',
            'date' => new \DateTime()
        );

        return $this->render('OCSymfonyBundle:Advert:view.html.twig', array('advert' => $advert));
    }

    Public function indexAction($page) {

        // Accès au conteneur
        $mailer = $this->get('mailer');

        // Appel du template
        return $this->render('OCSymfonyBundle:Advert:index.html.twig', array('listAdverts' => array()));
    }
}