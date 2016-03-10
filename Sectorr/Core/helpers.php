<?php

/**
 * Sectorr - The simplified PHP framework for creative architects.
 *
 * This file contains all Sectorr helpers.
 */

use Sectorr\Core\Config;
use Sectorr\Core\Http\Redirect;
use Sectorr\Core\Http\Route;
use Sectorr\Core\View;

if (! function_exists('view')) {
    /**
     * Helper: Make a view.
     *
     * @param $view
     * @return string
     * @throws \Sectorr\Core\Exceptions\ViewNotFoundException
     */
    function view($view, $data = [])
    {
        return View::make($view, $data);
    }
}



if (! function_exists('dd')) {

    /**
     * Helper: Dump the passed variables and stop the rest of the script.
     */
    function dd()
    {
        array_map(function ($x) {
            var_dump($x);
        }, func_get_args());
        die(1);
    }
}

if(! function_exists('isHttps')) {
    
    /**
     * Helper: Returns if a secure connection has been used.
     *
     * @return bool
     */
    function isHttps() {
        return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off');
    }
}

if (! function_exists('asset')) {

    /**
     * Helper: Returns a specified path by the user in the public directory.
     *
     * @param $userPath
     * @return string
     */
    function asset($userPath)
    {
        $path = (isHttps() ? 'https' : 'http') . '://'.$_SERVER['HTTP_HOST'];
        if(substr($userPath, 1, 1) == '/') {
            return $path . $userPath;
        }
        return $path . '/' . $userPath;
    }
}

if (! function_exists('config')) {
    /**
     * Helper: Get a config item.
     *
     * @param $key
     * @return mixed
     */
    function config($key)
    {
        return Config::get($key);
    }
}

if (! function_exists('redirect')) {
    /**
     * Helper: Redirects to the given route.
     *
     * @param $route
     */
    function redirect($route, $properties = [])
    {
        return Redirect::route($route, $properties);
    }
}

if (! function_exists('url')) {
    /**
     * Helper: Redirect to the given url.
     *
     * @param $url
     */
    function url($url)
    {
        return Redirect::url($url);
    }
}

if (! function_exists('route')) {
    /**
     * Helper: Returns the url of a route.
     *
     * @param $route
     * @return mixed
     */
    function route($route, $properties = [])
    {
        return Route::findRoute($route, $properties);
    }
}
