<?php

namespace OCSymfony\PlatformBundle\Purge;


use Doctrine\ORM\EntityManagerInterface;

class OCPurge {

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * OCPurge constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    /**
     * Purge des annonces sans applications datant de X jours
     *
     * @param $days
     * @return bool
     */
    public function purge($days) {

        return $this->em->getRepository('OCSymfonyPlatformBundle:Advert')->deleteAdvertsWithoutApplications($days);
        // Accès au repository
//        $advert = $this->em->getRepository('OCSymfonyPlatformBundle:Advert');
//
//        // Récupération des annonces sans applications
//        $adverts = $advert->getAdvertsWithoutApplications($days);
//
//        // Compteur du nombres d'annonces pour la condition
//        $nbAdvert = count($adverts);
//
//        // Condition par rapport au nombre d'annonce
//        if ($nbAdvert != 0) {
//
//            // Parcours des annonces et suppression
//            foreach ($adverts as $advert) {
//                $this->em->remove($advert);
//                $this->em->flush();
//                return true;
//            }
//        } else {
//            // Ne fait rien, renvoi faux
//            return false;
//        }
    }
}