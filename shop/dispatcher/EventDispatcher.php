<?php

namespace shop\dispatcher;

interface EventDispatcher
{
    public function dispatch($event): void;
}