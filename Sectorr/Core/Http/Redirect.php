<?php

namespace Sectorr\Core\Http;

class Redirect
{

    /**
     * Sets redirect header.
     *
     * @param $url
     * @return mixed
     */
    protected static function redirect($url)
    {
        return header("Location: {$url}");
    }

    /**
     * Redirects to the given url.
     *
     * @param $url
     * @return mixed
     */
    public static function url($url)
    {
        if (substr($url, 0, 1) == '/') {
            $url = substr_replace($url, '', 0, 1);
        }

        return self::redirect("/{$url}");
    }

    /**
     * Redirects to the given route.
     *
     * @param $route
     * @param array $properties
     * @return mixed
     * @throws \Sectorr\Core\Exceptions\RouteNotFoundException
     */
    public static function route($route, $properties = [])
    {
        return self::url(Route::findRoute($route, $properties));
    }

    /**
     * Redirect to HTTP referer.
     *
     * @return mixed
     */
    public static function back()
    {
        $referer = $_SERVER['HTTP_REFERER'];

        if (! empty($referer)) {
            return self::redirect($referer);
        }

        return self::redirect('/');
    }
}
