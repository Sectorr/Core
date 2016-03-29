<?php

namespace Sectorr\Core\Contracts;

interface MiddlewareContract
{
    /**
     * @param $route
     * @param $properties
     * @return mixed
     */
    public static function allow($route, array $properties);
}
