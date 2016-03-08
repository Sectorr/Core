<?php

/**
 * Sectorr - The simplified PHP framework for creative architects.
 *
 * @package  Sectorr
 * @author   Mirko Kroese <mirkokroese@gmail.com>
 * @author   Janyk Steenbeek <janyksteenbeek@gmail.com>
 */

namespace Sectorr\Core;

use Sectorr\Core\Http\Route;

class App
{
    /**
     * Boot Sectorr application.
     */
    public function boot()
    {
        session_start();
        $this->registerWhoops();

        $route = (empty($_REQUEST['_']) ? '/' : htmlspecialchars($_REQUEST['_']));

        require_once(PATH . '/app/routes.php');

        return Route::execute($route);
    }

    /**
     * Register Whoops error handler.
     */
    protected function registerWhoops()
    {
        $whoops = new \Whoops\Run;
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
        $whoops->register();
    }
}
