<?php

namespace OCSymfony\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class AdvertController extends Controller {

    Public function indexAction() {
        // Récupération du contenu
        $content = $this
            ->get('templating')
            ->render('OCSymfonyBundle:Advert:index.html.twig', array('nom' => 'Eyah'));

        // Génération de la vue avec le contenu
        return new Response($content);

    }
}