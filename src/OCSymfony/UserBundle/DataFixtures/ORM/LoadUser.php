<?php

namespace OCSymfony\UserBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use OCSymfony\UserBundle\Entity\User;

class LoadUser implements FixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager) {

        // Création de la listes des utilisateurs en dur
        $listnames = array('Alexandre', 'Marine', 'Anna');

        // Parcours de la liste
        foreach ($listnames as $name) {

            // Création de l'utilisateur
            $user = new User();

            // Définition du nom de l'utilisateur et du mot de passe
            $user->setUsername($name);
            $user->setPassword($name);

            // Définition du salt à vide pour l'instant
            $user->setSalt('');

            // Définition du role de base de chaque utilisateur
            $user->setRoles(array('ROLE_AUTEUR'));

            // Enregistrement
            $manager->persist($user);
        }

        // Execution de la requête
        $manager->flush();
    }
}