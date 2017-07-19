<?php

namespace OCSymfony\PlatformBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use OCSymfony\PlatformBundle\Entity\Skill;

class LoadSkill implements FixtureInterface {

    public function load(ObjectManager $manager) {
        // listes des noms de compétences
        $names = array('PHP', 'Symfony', 'C++', 'Java', 'Photoshop', 'Blender', 'Bloc-note');

        foreach ($names as $name) {
            // Création de la compétence
            $skill = new Skill();
            $skill->setName($name);

            // Persiste
            $manager->persist($skill);
        }

        // Execution de l'enregistrement
        $manager->flush();
    }
}