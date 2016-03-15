<?php

namespace Sectorr\Core;

use Sectorr\Core\Exceptions\ViewNotFoundException;
use duncan3dc\Laravel\Blade;
use duncan3dc\Laravel\BladeInstance;

class View
{

    public static function make($view, $data = [])
    {
        $blade = new BladeInstance(PATH . "/app/Views", sys_get_temp_dir());

        $location = PATH . '/app/Views/' . $view . '.php';

        if (! file_exists($location)) {
            throw new ViewNotFoundException($view);
        }

        return $blade->render($view, $data);
    }
}
