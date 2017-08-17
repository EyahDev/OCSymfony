<?php

namespace OCSymfony\PlatformBundle\Controller;

use OCSymfony\PlatformBundle\Form\AdvertEditType;
use OCSymfony\PlatformBundle\Form\AdvertType;
use OCSymfony\PlatformBundle\Entity\Advert;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Finder\Exception\AccessDeniedException;
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
            $session->getFlashBag()->add('notice', 'Purge : Les annonces ont bien été supprimées '.$traitement);

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

    public function deleteAction (Request $request,Session $session, $id) {

        // Accès à l'EntityManager
        $em = $this->getDoctrine()->getManager();

        // Récupération de l'annonce lié à l'id
        $advert = $em->getRepository('OCSymfonyPlatformBundle:Advert')->find($id);

        // Retourne un message d'erreur si l'annonce n'existe pas
        if (null === $advert) {
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        }

        // Création d'un formulaire vide pour la protection contre la faille CSRF
        $form = $this->get('form.factory')->create();

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()){

            // Parcours des catégories et suppression
            foreach ($advert->getCategories() as $category) {
                $advert->removeCategory($category);
            }
            // Suppression de l'annonce
            $em->remove($advert);

            // Execution des suppression
            $em->flush();

            // création d'un message flash
            $session->getFlashBag()->add('notice', 'l\'annonce bien été supprimée');

            // Redirection vers la page d'accueil
            return $this->redirectToRoute('oc_symfony_platform_homepage');
        }

        // Chargement d'une page de confirmation avant la suppression
        return $this->render('OCSymfonyPlatformBundle:Advert:delete.html.twig', array(
            'advert' => $advert,
            'form' => $form->createView()
        ));
    }

    public function editAction ($id, Request $request, Session $session) {

        // Accès à l'EntityManager
        $em = $this->getDoctrine()->getManager();

        // Récupération de l'annonce
        $advert = $em->getRepository('OCSymfonyPlatformBundle:Advert')->find($id);

        if (null === $advert) {
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        }

        // Création du formulaire de modification de l'annonce
        $form = $this->createForm(AdvertEditType::class, $advert);

        // Si la requête est en POST et que les champs sont valide
        if ($request->isMethod('POST') && $form->handleRequest($advert)->isValid()) {

            $em->flush();

            // Création d'un message flash à la création de l'annonce
            $session->getFlashBag()->add('notice', 'l\'annonce à bien été modifiée');

            // Redirection vers la page de l'annonce nouvellement créee
            return $this->redirectToRoute('oc_symfony_platform_view', array(
                'slug' => $advert->getSlug(),
                'id' => $id
            ));
        }

        // Si ce n'est pas POST
        return $this->render('OCSymfonyPlatformBundle:Advert:edit.html.twig', array(
            'form' => $form->createView(),
            'advert' => $advert
            ));
    }

    /**
     * @param Request $request
     * @param Session $session
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function addAction(Request $request, Session $session) {

        /*
        // Vérification du role de l'utilisateur
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_AUTEUR')) {

            // Génère une page "Accès refusé"
            throw new AccessDeniedException('Accès limités aux auteurs');
        }
        */

        // Accès à l'EntityManager
        $em = $this->getDoctrine()->getManager();

        // Création de l'entité Advert
        $advert = new Advert();

        // Création du formulaire de création d'annonce
        $form = $this->createForm(AdvertType::class, $advert);

        // Si la requête est en POST création du lien Requête <-> Formulaire
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            $em->persist($advert);
            $em->flush();

            // Création d'un message flash à la création de l'annonce
            $session->getFlashBag()->add('notice', 'l\'annonce à bien été enregistrée');

            // Redirection vers la page de l'annonce nouvellement créee
            return $this->redirectToRoute('oc_symfony_platform_view', array('slug' => $advert->getSlug()));

        }

        // Si ce n'est pas POST
        return $this->render('OCSymfonyPlatformBundle:Advert:add.html.twig', array('form' => $form->createView()));

    }

    /**
     * @param Advert $advert
     * @return Response
     *
     * @ParamConverter("advert", options={"mapping": {"slug": "slug"}})
     */
    public function viewAction(Advert $advert) {

        // Récupération du repository
        $em = $this->getDoctrine()->getManager();

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

    public function translationAction($name) {
        return $this->render('OCSymfonyPlatformBundle:Advert:translation.html.twig', array('name' => $name));
    }
}