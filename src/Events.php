<?php

namespace App;

final class Events
{
    /**
    * @Event("Symfony\Component\EventDispatcher\GenericEvent")
    *
    * @var string
    */
    const USER_REGISTERED = 'user.registered';
}