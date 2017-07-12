<?php

namespace OCSymfony\Antispam;


class OCAntispam {

    private $mailer;
    private $locale;
    private $minLenght;

    public function __construct(\Swift_Mailer $mailer, $locale, $minlenght) {
        $this->mailer = $mailer;
        $this->locale = $locale;
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
}