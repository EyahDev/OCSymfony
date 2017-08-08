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
                    'title' => 'Annonce 1',
                    'author' => 'Alexandre',
                    'content' => 'Contenu'
                ),
                array(
                    'title' => 'Annonce 2',
                    'author' => 'Alexandre',
                    'content' => 'Contenu'
                ),
                array(
                    'title' => 'Annonce 3',
                    'author' => 'Alexandre',
                    'content' => 'Contenu'
                ),
                array(
                    'title' => 'Annonce 4',
                    'author' => 'Alexandre',
                    'content' => 'Contenu'
                ),
                array(
                    'title' => 'Annonce 5',
                    'author' => 'Alexandre',
                    'content' => 'Contenu'
                ),
                array(
                    'title' => 'Annonce 6',
                    'author' => 'Alexandre',
                    'content' => 'Contenu'
                ),
                array(
                    'title' => 'Annonce 7',
                    'author' => 'Alexandre',
                    'content' => 'Contenu'
                ),
                array(
                    'title' => 'Annonce 8',
                    'author' => 'Alexandre',
                    'content' => 'Contenu'
                ),
        );

        foreach ($adverts as $ad) {
            // Création des annonces
            $advert = new Advert();
            $advert->setTitle($ad['title']);
            $advert->setAuthor($ad['author']);
            $advert->setContent($ad['content']);

            // Enregistrement
            $manager->persist($advert);
        }

        // Execution
        $manager->flush();
    }

}