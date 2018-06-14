<?php

namespace App;

final class Events
{
    /**
    * @Event("Symfony\Component\EventDispatcher\GenericEvent")
    * Send a mail when an user registered
    * @var string
    */
    const USER_REGISTERED = 'user.registered';

    /**
     * @Event("Symfony\Component\EventDispatcher\GenericEvent")
     * Send a mail to reset a password
     * @var string
     */
    const NEW_PASSWORD_REQUESTED = 'new.password.requested';
}