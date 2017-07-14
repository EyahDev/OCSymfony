<?php

namespace OCSymfony\PlatformBundle\Controller;

use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;

class AdvertController extends Controller {

    public function menuAction() {

        // liste du menu
        $listAdverts = array(
            array('id' => 2, 'title' => 'Recherche développeur Symfony'),
            array('id' => 5, 'title' => 'Mission de webmaster'),
            array('id' => 9, 'title' => 'Offre de stage webdesigner')
        );

        // Retourne le menu avec la liste
        return $this->render('OCSymfonyPlatformBundle:Advert:menu.html.twig', array('listAdverts' => $listAdverts));
    }

    public function deleteAction ($id, Request $request, Session $session) {

        $session->getFlashBag()->add('info','La page de suppression de l\'annonce n\'est pas encore disponible, merci de revenir plus tard');

        // Appel du template
        return $this->redirectToRoute('oc_symfony_platform_view', array('id' => $id));
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

            $session->getFlashBag()->add('notice', 'L\'annonce à bien été modifiée');

            // Redirection vers la page de visualisation de cette annonce
            return $this->redirectToRoute('oc_platform_view', array('id' => $id));
        }
        // Si ce n'est pas POST
        return $this->render('OCSymfonyPlatformBundle:Advert:edit.html.twig', array('advert' => $advert));

    }

    public function addAction(Request $request, Session $session) {

        // Récupération du service
        $antispam = $this->container->get('oc_symfony_platform.antispam');

        $text = "....";

        if ($antispam->isSpam($text)) {
            throw new Exception('Votre message été détecté comme spam');
        }

        // Vérification si la requête est en POST
        if ($request->isMethod('POST')) {
            // Création du message flash de confirmation
            $session->getFlashBag()->add('notice', 'L\'annonce à bien été enregistrée');

            // Redirection vers la page de visualisation de cette annonce
            return $this->redirectToRoute('oc_platform_view', array('id' => 5));
        }
        // Si ce n'est pas POST
        return $this->render('OCSymfonyPlatformBundle:Advert:add.html.twig');

    }

    public function viewAction($id) {

        $advert = array(
            'title' => 'Recherche développeur Symfony2',
            'id' => $id,
            'author' => 'Alexandre',
            'content' => 'Nous rechercons un développeur Symfony2 pour débuytant sur Lyon. Bla bla...',
            'date' => new \DateTime()
        );

        return $this->render('OCSymfonyPlatformBundle:Advert:view.html.twig', array('advert' => $advert));
    }

    Public function indexAction($page) {

        // Accès au conteneur
        $mailer = $this->get('mailer');

        // Appel du template
        return $this->render('OCSymfonyPlatformBundle:Advert:index.html.twig', array('listAdverts' => array()));
    }
}