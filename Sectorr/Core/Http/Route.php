<?php

namespace Sectorr\Core\Http;

use Sectorr\Core\Exceptions\ControllerNotFoundException;
use Sectorr\Core\Exceptions\MiddlewareNotFoundException;
use Sectorr\Core\Exceptions\RouteNotFoundException;

class Route
{

    private static $routes = [];
    private static $namespace = 'App\Controllers\\';
    private static $middlewareNamespace = 'App\Middleware\\';

    /**
     * Add GET route to route list.
     *
     * @param $url
     * @param $props
     */
    public static function get($url, $props)
    {
        self::add($url, $props, 'GET');
    }

    /**
     * Add POST route to route list.
     *
     * @param $url
     * @param $props
     */
    public static function post($url, $props)
    {
        self::add($url, $props, 'POST');
    }

    /**
     * Returns all created routes.
     *
     * @return array
     */
    public static function getRoutes()
    {
        return self::$routes;
    }

    /**
     * Finds a route by the given route name.
     * Also takes properties to put into the route url.
     *
     * @param $route
     * @param array $properties
     * @return string
     */
    public static function findRoute($route, $properties = [])
    {
        // Check if properties for dynamic routing are given.
        if (! empty($properties)) {

            // Loop through all the created routes
            foreach (self::$routes as $r) {

                // Check if the given route name is equal to the route in the current loop.
                if ($r['props']['as'] == $route) {

                    // Divide the url in different parts by exploding on a '/'.
                    $parts = explode('/', $r['url']);
                    $url = "";  // Creates empty string to put in the dynamic url.
                    $count = 1; // Create a counter to check if reached the last piece of the url.
                    foreach ($parts as $part) {

                        // Check if the current piece is a dynamic piece.
                        if (substr($part, 0, 1) == '{' && substr($part, -1, 1) == '}') {
                            $prop = str_replace('}', '', str_replace('{', '', $part));

                            // Get the needed property and add it to the url
                            $url .= (count($parts) == $count ? $properties[$prop] : $properties[$prop]."/");
                        } else {
                            $url .= (count($parts) == $count ? $part : $part."/");
                        }
                        $count++;
                    }
                    return $url;
                }
            }
        }

        foreach (self::$routes as $r) {
            if ($r['props']['as'] == $route) {
                return $r['url'];
            }
        }
    }

    /**
     * Add route to route list.
     *
     * @param $url
     * @param $props
     * @param $method
     */
    private static function add($url, $props, $method)
    {
        self::$routes[] = ['url' => $url, 'props' => $props, 'method' => $method];
    }

    /**
     * Call controller by route.
     *
     * @param $route
     * @param array $properties
     * @return
     * @throws ControllerNotFoundException
     */
    private static function callController($route, $properties = [])
    {
        $props = $route['props'];
        $uses = $props['uses'];

        // Check if any middleware has been set.
        if(! empty($props['middleware'])) {
            if(! ctype_upper(substr($props['middleware'], 0, 1))) {
                $middleware = substr_replace($props['middleware'], strtoupper(substr($props['middleware'], 0, 1)), 0);
                $middleware .= substr($props['middleware'],1,strlen($props['middleware']));
            } else {
                $middleware = $props['middleware'];
            }

            if(! file_exists(PATH . '/app/Middleware/' . $middleware . '.php')) {
                throw new MiddlewareNotFoundException($middleware);
            }
            $class = self::$middlewareNamespace . $middleware;
            $allowal = $class::allow();

            if($allowal !== true) {
                return $allowal;
            }
        }

        $controller = explode('@', $uses)[0];
        $method = explode('@', $uses)[1];

        if (file_exists(PATH . '/app/Controllers/' . $controller . '.php')) {

            // Calling controller method.
            $controller = self::$namespace . $controller;
            $cont = new $controller;

            return $cont->$method($properties);
        }
        throw new ControllerNotFoundException($controller);
    }

    /**
     * Execute routing.
     *
     * @param $currentRoute
     * @throws RouteNotFoundException
     * @return
     */
    public static function execute($currentRoute)
    {
        $currentRouteParts = explode('/', $currentRoute);

        foreach (self::$routes as $route) {

            // Check if route matches complete URL.
            if ($route['url'] == $currentRoute) {
                return self::callController($route);
            }

            // Exploding route parts for dynamic checking.
            $routeParts = explode('/', $route['url']);

            // If route parts does not match current URL parts, continue.
            if (count($currentRouteParts) !== count($routeParts)) {
                continue;
            }

            $parts = count($routeParts) - 1;
            $valid = true;
            $properties = [];

            foreach ($routeParts as $key => $routePart) {
                $dynamicRoute = false;

                // Current route is not valid, continuing.
                if (! $valid) {
                    continue;
                }

                // Current route part is dynamic, add to array.
                if (substr($routePart, 0, 1) == '{' && substr($routePart, -1, 1) == '}') {
                    $properties[substr($routePart, 1, -1)] = $currentRouteParts[$key];
                    $dynamicRoute = true;
                }

                // If is not dynamic part, and does not match current URL part, make route invalid and continue.
                if (! $dynamicRoute && $routePart != $currentRouteParts[$key]) {
                    $valid = false;
                    continue;
                }

                // Check passed, call controller for current route.
                if ($key == $parts && $valid == true) {
                    return self::callController($route, $properties);
                }
            }
        }

        // No valid route for current route, throw exception.
        throw new RouteNotFoundException($currentRoute);
    }
}
