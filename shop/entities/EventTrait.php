<?php

namespace shop\entities;

trait EventTrait
{
    private $events = [];

    public function recordEvent($event) :void
    {
        $this->events = $event;
    }

    public function releaseEvent() :array
    {
        $events = $this->events;
        $this->events = [];
        return $events;
    }
}