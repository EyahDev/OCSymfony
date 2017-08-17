<?php
/**
 * Created by PhpStorm.
 * User: Adrien
 * Date: 11/08/2017
 * Time: 11:42
 */

namespace OCSymfony\PlatformBundle\Twig;


use OCSymfony\PlatformBundle\Antispam\OCAntispam;

class AntispamExtension extends \Twig_Extension {

    /**
     * @var OCAntispam
     */
    private $ocAntispam;

    public function __construct(OCAntispam $ocAntispam) {
        $this->ocAntispam = $ocAntispam;
    }

    public function checkIfArgumentIsSpam($text) {
        return $this->ocAntispam->isSpam($text);
    }

    public function getFunction() {
        return array(
            new \Twig_SimpleFunction('checkIfSpam', array($this->ocAntispam, 'isSpam')),
        );
    }

    public function getName() {
        return 'OCantispam';
    }

}