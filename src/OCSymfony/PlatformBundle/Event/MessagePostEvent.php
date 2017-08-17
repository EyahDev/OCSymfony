<?php

namespace OCSymfony\PlatformBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Security\Core\User\UserInterface;

class MessagePostEvent extends Event {
    /**
     * @var
     */
    protected $message;

    /**
     * @var UserInterface
     */
    protected $user;

    /**
     * MessagePostEvent constructor.
     * @param $message
     * @param UserInterface $user
     */
    public function __construct($message, UserInterface $user) {
        $this->message = $message;
        $this->user = $user;
    }

    /**
     * Accès au message
     *
     * @return mixed
     */
    public function getMessage() {
        return $this->message;
    }

    /**
     * Modification du message
     *
     * @param $message
     * @return mixed
     */
    public function setMessage($message) {
        return $this->message = $message;
    }

    /**
     * Récupération de l'utilisateur
     *
     * @return UserInterface
     */
    public function getUser() {
        return $this->user;
    }


}