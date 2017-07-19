<?php

namespace OCSymfony\PlatformBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use OCSymfony\PlatformBundle\Entity\Category;

class LoadCategory implements FixtureInterface {

    public function load(ObjectManager $manager) {
        // Listes des noms de catégories à ajouter
        $names = array(
            'Developpement web',
            'Developpement mobile',
            'Graphisme',
            'Intégration',
            'Réseau'
        );

        foreach ($names as $name) {
            // Création de la catégorie
            $category = new Category();
            $category->setName($name);

            // Enregistrement
            $manager->persist($category);
        }

        // Execution
        $manager->flush();
    }
}