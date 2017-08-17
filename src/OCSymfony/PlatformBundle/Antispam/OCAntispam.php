<?php

namespace OCSymfony\PlatformBundle\Antispam;


class OCAntispam {

    private $mailer;
    private $locale;
    private $minLenght;

    public function __construct(\Swift_Mailer $mailer, $minlenght) {
        $this->mailer = $mailer;
        $this->minLenght = (int) $minlenght;
    }

    /**
     * Vérification si c'est un spam ou non
     *
     * @param string $text
     * @return bool
     */
    public function isSpam($text) {
        return strlen($text) < $this->minLenght;
    }

    public function setLocale($locale) {
        $this->locale = $locale;
    }
}