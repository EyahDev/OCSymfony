<?php
/**
 * Created by PhpStorm.
 * User: Adrien
 * Date: 14/08/2017
 * Time: 12:42
 */

namespace OCSymfony\PlatformBundle\Beta;


use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class BetaListener {

    // Processeur
    protected $betaHTML;

    // Date de fin de la bêta
    protected $endDate;

    /**
     * BetaListener constructor.
     * @param BetaHTMLAdder $betaHTML
     * @param $endDate
     */
    public function __construct(BetaHTMLAdder $betaHTML, $endDate) {
        $this->betaHTML = $betaHTML;
        $this->endDate = new \DateTime($endDate);
    }

    public function processBeta(FilterResponseEvent $event) {
        // On test si la requête et bien la requête principale
        if (!$event->isMasterRequest()) {
            return;
        }

        $remainingDays = $this->endDate->diff(new \DateTime())->days;
        // Si la date est depassée, on ne fait rien
        if ($remainingDays <= 0) {
            return;
        }

        // Récupération de la réponse modifiée avant l'insersion
        $response = $this->betaHTML->addBeta($event->getResponse(), $remainingDays);

        // Insersion de la réponse dans la requête
        $event->setResponse($response);
    }

}