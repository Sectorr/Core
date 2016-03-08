<?php

namespace Sectorr\Core\Http;

class Redirect
{

    /**
     * Redirects to the given url.
     *
     * @param $url
     */
    public static function url($url)
    {
        if (substr($url, 0, 1) == '/') {
            $url = substr_replace($url, '', 0, 1);
        }
        header("Location: /{$url}");
    }

    /**
     * Redirects to the given route.
     *
     * @param $route
     */
    public static function route($route, $properties = [])
    {
        self::url(Route::findRoute($route, $properties));
    }
}
