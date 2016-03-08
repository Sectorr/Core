<?php

namespace Sectorr\Core\Exceptions;

class MiddlewareNotFoundException extends \Exception
{
    protected $middleware;

    public function __construct($middleware)
    {
        parent::__construct();

        $this->middleware = $middleware;
        $this->message = 'Middleware ' . $middleware . ' could not been found.';
    }

    public function getMiddleware()
    {
        return $this->middleware;
    }
}
