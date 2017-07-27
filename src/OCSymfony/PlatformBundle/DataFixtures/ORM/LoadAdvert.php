<?php

namespace OCSymfony\PlatformBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use OCSymfony\PlatformBundle\Entity\Advert;

class LoadAdvert implements FixtureInterface {

    public function load(ObjectManager $manager) {

        // Listes des annonces à ajouter
        $adverts =
            array(
                array(
                    'Annonce 1' => 'title',
                    'Alexandre' => 'author',
                    'Contenu' => 'content'
                ),
                array(
                    'Annonce 2' => 'title',
                    'Alexandre' => 'author',
                    'Contenu' => 'content'
                ),
                array(
                    'Annonce 3' => 'title',
                    'Alexandre' => 'author',
                    'Contenu' => 'content'
                ),
                array(
                    'Annonce 4' => 'title',
                    'Alexandre' => 'author',
                    'Contenu' => 'content'
                ),
                array(
                    'Annonce 5' => 'title',
                    'Alexandre' => 'author',
                    'Contenu' => 'content'
                ),
                array(
                    'Annonce 6' => 'title',
                    'Alexandre' => 'author',
                    'Contenu' => 'content'
                ),
                array(
                    'Annonce 6' => 'title',
                    'Alexandre' => 'author',
                    'Contenu' => 'content'
                ),
                array(
                    'Annonce 7' => 'title',
                    'Alexandre' => 'author',
                    'Contenu' => 'content'
                ),
        );

        foreach ($adverts as $advert) {
            foreach ($advert as $content) {

                // Création des annonces
                $advert = new Advert();
                $advert->setTitle($content);
                $advert->setAuthor($content);
                $advert->setContent($content);

                // Enregistrement
                $manager->persist($advert);
            }

        }

        // Execution
        $manager->flush();
    }

}