<?php

namespace EB\RideBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class NotificationEvent extends Event
{
    private $options;

    public function __construct($options)
    {
        $this->options = $options;
    }

    public function get($key)
    {
        if (array_key_exists($key, $this->options)) {
            return $this->options[$key];
        }

        throw new \Exception('Could not find the provided key.');
    }
}
