<?php

namespace OCSymfony\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class AdvertController extends Controller {

    public function viewSlugAction($slug, $year, $_format) {

        return new Response(
            "On pourrait afficher l'annonce correspondant au slug '".$slug."', créée en ".$year." et au format ".$_format."."
        );
    }

    public function viewAction($id) {

        return new Response("Affichage de l'annonce d'id : " .$id);
    }

    Public function indexAction() {
        // Récupération du contenu avec la vue
        $url = $this->get('router')->generate(
            'oc_platform_view',
            array('id' => 5)
        );

        // Retourne la vue avec le contenu
        return new Response("L'URL de l'annonce d'id 5 est : ".$url);
    }
}