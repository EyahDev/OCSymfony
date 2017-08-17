<?php
/**
 * Created by PhpStorm.
 * User: Adrien
 * Date: 14/08/2017
 * Time: 12:29
 */

namespace OCSymfony\PlatformBundle\Beta;


use Symfony\Component\HttpFoundation\Response;

class BetaHTMLAdder {

    /**
     * Méthode pour ajouter le bêta à une réponse
     *
     * @param Response $response
     * @param $remainingDays
     */
    public function addBeta(Response $response, $remainingDays) {

        // Récupération du contenu
        $content = $response->getContent();

        $html = '<div style="position: absolute; top: 0; background: orange; width: 100%; text-align: center; padding: 0.5em;">Beta J-'.(int) $remainingDays. ' !</div>';

        // Insersion du code dans la page au début de body
        $content = str_replace(
            '<body>',
            '<body>'.$html,
            $content);

        // Modification du contentu dans la réponse
        $response->setContent($content);

        return $response;
    }
}