<?php

namespace Sectorr\Core\Exceptions;

class RouteNotFoundException extends \Exception
{
    protected $route;

    public function __construct($route)
    {
        parent::__construct();

        $this->route = $route;
        $this->message = 'Route ' . $route . ' could not been found.';
    }

    public function getRoute()
    {
        return $this->route;
    }
}
