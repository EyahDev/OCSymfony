<?php

namespace OCSymfony\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CoreController extends Controller {

    public function indexAction() {
        // Appel du template
        return $this->render('OCSymfonyCoreBundle:Core:index.html.twig');
    }

    public function contactAction(Request $request) {

        $request->getSession()->getFlashBag()->add('info','La page de contact n\'est pas encore disponible, merci de revenir plus tard');
        // Appel du template
        return $this->redirectToRoute('oc_symfony_core_homepage');
    }
}