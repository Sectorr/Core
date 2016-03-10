<?php

namespace Sectorr\Core;

use Sectorr\Core\Exceptions\ViewNotFoundException;

class View
{

    public static function make($view, $data = [])
    {
        $location = PATH . '/app/Views/' . $view . '.php';

        if (! file_exists($location)) {
            throw new ViewNotFoundException($view);
        }

        ob_start();

        foreach ($data as $key => $value) {
            $varname = $key;
            $$varname = $value;
        }

        require_once($location);

        $string = ob_get_contents();
        ob_end_clean();

        return $string;
    }
}
