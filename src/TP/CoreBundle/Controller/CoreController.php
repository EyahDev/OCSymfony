<?php

namespace TP\CoreBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CoreController extends Controller {

    public function indexAction() {

        // Appel du template
        return $this->render('TPCoreBundle:Core:index.html.twig', array('listAdverts' => array()));
    }


}