<?php

namespace OCSymfony\PlatformBundle\Controller;

use OCSymfony\PlatformBundle\Entity\Advert;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

        // Création de l'entité
        $advert = new Advert();
        $advert->setTitle('Recherche de développeur Symfony.');
        $advert->setAuthor('Alexandre');
        $advert->setContent('Nous recherchons un développeur Symfony débutant sur Lyon...Blablablabla...');
        // Date et publication sont définis par défaut

        // Récupération de l'EntityManager
        $em = $this->getDoctrine()->getManager();

        // Etape 1 : On "persiste" l'entité
        $em->persist($advert);

        // Etape 2 : on "flush" tout ce a été persisté avant
        $em->flush();


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

        // Récupération du repository
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('OCSymfonyPlatformBundle:Advert');

        // Récupération de l'entité correspondante à l'id
        $advert = $repository->find($id);

        if (null === $advert) {
            throw new NotFoundHttpException("L'annonce d'id ".id."n'existe pas.");
        }

        return $this->render('OCSymfonyPlatformBundle:Advert:view.html.twig', array('advert' => $advert));
    }

    Public function indexAction($page) {

        // Accès au conteneur
        $mailer = $this->get('mailer');

        // Appel du template
        return $this->render('OCSymfonyPlatformBundle:Advert:index.html.twig', array('listAdverts' => array()));
    }
}