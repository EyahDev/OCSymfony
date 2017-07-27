<?php

namespace OCSymfony\PlatformBundle\Controller;

use OCSymfony\PlatformBundle\Entity\Advert;
use OCSymfony\PlatformBundle\Entity\AdvertSkill;
use OCSymfony\PlatformBundle\Entity\Application;
use OCSymfony\PlatformBundle\Entity\Image;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdvertController extends Controller {

    /**
     * Action de purge des annonces datant de X jours
     *
     * @param $days
     * @return Response
     */
    public function purgeAction(Session $session, $days) {
        // Récupération du service
        $purge = $this->get('oc_symfony_platform.purge.advert');

        //Lancement de la purge
        $traitement = $purge->purge($days);

        // Condition si la purge à eu lieux ou non
        if ($traitement) {
            // Création du message flash de confirmation
            $session->getFlashBag()->add('notice', 'Purge : Les annonces ont bien été supprimer');

            // Redirection vers la page d'accueil
            return $this->redirectToRoute('oc_symfony_platform_homepage');
        } else {
            // Création du message flash de confirmation
            $session->getFlashBag()->add('notice', 'Purge : Aucune annonce à supprimer');

            // Redirection vers la page d'accueil
            return $this->redirectToRoute('oc_symfony_platform_homepage');
        }

    }

    public function testAction() {
        $advert = new Advert();

        $advert->setTitle('Recherche développeur');

        $em = $this->getDoctrine()->getManager();
        $em->persist($advert);
        $em->flush();

        return new Response('Slug généré : '.$advert->getSlug());
    }

    public function editImageAction($advertId) {

        // Accès au EntityManager
        $em = $this->getDoctrine()->getManager();

        // On récupère l'annonce
        $advert = $em->getRepository('OCSymfonyPlatformBundle:Advert')->find($advertId);

        // Modification de l'url de l'image
        $advert->getImage()->setUrl('test.png');

        // Execution de la modification
        $em->flush();

        return new Response('OK');
    }

    public function menuAction($limit) {

        // Accès à l'EntityManager
        $em = $this->getDoctrine()->getManager();
        // liste du menu
        $listAdverts = $em->getRepository('OCSymfonyPlatformBundle:Advert')->findBy(array(), array('date' => 'desc'), $limit, 0);

        // Retourne le menu avec la liste
        return $this->render('OCSymfonyPlatformBundle:Advert:menu.html.twig', array('listAdverts' => $listAdverts));
    }

    public function deleteAction ($id) {

        // Accès à l'EntityManager
        $em = $this->getDoctrine()->getManager();

        // Récupération de l'annonce lié à l'id
        $advert = $em->getRepository('OCSymfonyPlatformBundle:Advert')->find($id);

        if (null === $advert) {
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        }

        foreach ($advert->getCategories() as $category) {
            $advert->removeCategory($category);
        }

        $em->flush();
    }

    public function editAction ($id, Request $request, Session $session) {

        // Accès à EntityManager
        $em = $this->getDoctrine()->getManager();

        // Récupération de l'annonce lié à l'id
        $advert = $em->getRepository('OCSymfonyPlatformBundle:Advert')->find($id);

        if (null === $advert) {
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        }

        // Récupération de toutes les catégories
        $listCategories = $em->getRepository("OCSymfonyPlatformBundle:Category")->findAll();

        foreach ($listCategories as $category) {
            $advert->addCategory($category);
        }

        $em->flush();

        // Vérification si la requête est en POST
        if ($request->isMethod('POST')) {

            // Création du message flash de confirmation
            $session->getFlashBag()->add('notice', 'L\'annonce à bien été modifiée');

            // Redirection vers la page de visualisation de cette annonce
            return $this->redirectToRoute('oc_symfony_platform_view', array('id' => $id));
        }
        // Si ce n'est pas POST
        return $this->render('OCSymfonyPlatformBundle:Advert:edit.html.twig', array('advert' => $advert));

    }

    public function addAction(Request $request, Session $session) {

        // Accès à l'EntityManager
        $em = $this->getDoctrine()->getManager();

        // Création de l'entité Advert
        $advert = new Advert();
        $advert->setTitle('Recherche de développeur Symfony.');
        $advert->setAuthor('Alexandre');
        $advert->setContent('Nous recherchons un développeur Symfony débutant sur Lyon...Blablablabla...');

        // Récupération des compétences
        $listSkill = $em->getRepository('OCSymfonyPlatformBundle:Skill')->findAll();

        // Pour chaque compétences
        foreach ($listSkill as $skill) {
            // Création d'une nouvelle relation
            $advertSkill = new AdvertSkill();

            // Liaison de l'annonce
            $advertSkill->setAdvert($advert);

            // liaison de la compétence à l'annonce
            $advertSkill->setSkill($skill);

            // liaison du niveau à l'annonce
            $advertSkill->setLevel('Expert');

            // Enregistrement

            $em->persist($advertSkill);
        }

        // Création de l'entité Image
        $image = new Image();
        $image->setUrl('http://sdz-upload.s3.amazonaws.com/prod/upload/job-de-reve.jpg');
        $image->setAlt('Job de rêve');

        // Liaison de l'image à l'annonce
        $advert->setImage($image);

        // Création de l'entité Application n° 1
        $application1 = new Application();
        $application1->setAuthor('Pierre');
        $application1->setContent('Je suis très motivé.');

        // Création de l'entité Application n° 2
        $application2 = new Application();
        $application2->setAuthor('Marine');
        $application2->setContent('J\'ai tous ce qu\'il faut !');

        // Liaison de les candidatures à l'annonce
        $application1->setAdvert($advert);
        $application2->setAdvert($advert);

        // Récupération de l'EntityManager
        $em = $this->getDoctrine()->getManager();

        // Etape 1 : On "persiste" l'entité
        $em->persist($advert);
        $em->persist($application1);
        $em->persist($application2);

        // Etape 2 : on "flush" tout ce a été persisté avant
        $em->flush();


        // Vérification si la requête est en POST
        if ($request->isMethod('POST')) {
            // Création du message flash de confirmation
            $session->getFlashBag()->add('notice', 'L\'annonce à bien été enregistrée');

            // Redirection vers la page de visualisation de cette annonce
            return $this->redirectToRoute('oc_symfony_platform_view', array('id' => 5));
        }
        // Si ce n'est pas POST
        return $this->render('OCSymfonyPlatformBundle:Advert:add.html.twig');

    }

    public function viewAction($id) {

        // Récupération du repository
        $em = $this->getDoctrine()->getManager();

        // Récupération de l'entité correspondante à l'id
        $advert = $em->getRepository('OCSymfonyPlatformBundle:Advert')->find($id);

        if (null === $advert) {
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        }

        // Récupération de la liste de candidatures de cette annonce
        $listApply = $em
            ->getRepository('OCSymfonyPlatformBundle:Application')
            ->findBy(array('advert' => $advert));

        // Récupération de la liste des compétences
        $listAdvertSkill = $em
            ->getRepository('OCSymfonyPlatformBundle:AdvertSkill')
            ->findBy(array('advert' => $advert));

        return $this->render('OCSymfonyPlatformBundle:Advert:view.html.twig', array(
            'advert' => $advert,
            'listApply' => $listApply,
            'listAdvertSkill' => $listAdvertSkill
        ));
    }

    Public function indexAction($page) {

        if ($page < 1) {
            throw new NotFoundHttpException('La page "'.$page.'" n\'existe pas');
        }

        $nbPerPage = 3;

        // Accès à l'EntityManager
        $em = $this->getDoctrine()->getManager();

        // Récupération de toutes les annonces
        $listAdverts = $em->getRepository('OCSymfonyPlatformBundle:Advert')->getAdverts($page, $nbPerPage);

        // Calcul du nombre total de pages necessaires
        $nbPages = ceil(count($listAdverts) / $nbPerPage);

        if ($page > $nbPages) {
            throw $this->createNotFoundException("La page" .$page. " n'existe pas.");
        }

        // Appel du template et passage des annonces en paramètres
        return $this->render('OCSymfonyPlatformBundle:Advert:index.html.twig', array(
            'listAdverts' => $listAdverts,
            'nbPages' => $nbPages,
            'page' => $page,
        ));
    }
}