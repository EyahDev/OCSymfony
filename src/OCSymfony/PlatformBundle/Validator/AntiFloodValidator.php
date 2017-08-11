<?php

namespace OCSymfony\PlatformBundle\Validator;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class AntiFloodValidator extends ConstraintValidator {

    private $requestStack;
    private $em;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $em) {
        $this->requestStack = $requestStack;
        $this->em = $em;
    }

    public function validate($value, Constraint $constraint) {

        // Récupération de la requête
        $request = $this->requestStack->getCurrentRequest();

        // Récupération de l'adresse ip
        $ip = $request->getClientIp();

        // Vérification si cette IP a déjà posté il y a moins de 15 sec
        $isFlood = $this->em
            ->getRepository('OCSymfonyPlatformBundle:Application')
            ->isFlood($ip, 15);

        // Vérification si flood
        if ($isFlood) {

            // Déclenchement de l'erreur
            $this->context->addViolation($constraint->message);
        }
    }

}