<?php

namespace Sectorr\Core\Exceptions;

class ControllerNotFoundException extends \Exception
{
    protected $controller;

    public function __construct($controller)
    {
        parent::__construct();

        $this->route = $controller;
        $this->message = 'Controller ' . $controller . ' could not been found.';
    }

    public function getController()
    {
        return $this->controller;
    }
}
