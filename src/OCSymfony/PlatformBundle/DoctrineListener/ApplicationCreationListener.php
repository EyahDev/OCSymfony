<?php

namespace OCSymfony\PlatformBundle\DoctrineListener;

use OCSymfony\PlatformBundle\Email\ApplicationMailer;
use OCSymfony\PlatformBundle\Entity\Application;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

class ApplicationCreationListener {

    /**
     * @var ApplicationMailer
     */
    private $applicationMailer;

    /**
     * ApplicationCreationListener constructor.
     * @param ApplicationMailer $applicationMailer
     */
    public function __construct(ApplicationMailer $applicationMailer) {
        $this->applicationMailer = $applicationMailer;
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postPersist(LifecycleEventArgs $args) {
        $entity = $args->getObject();
        // On ne veut envoyer un email que pour les entités Application
        if (!$entity instanceof Application) {
            return;
        }
        $this->applicationMailer->sendNewNotification($entity);
    }
}