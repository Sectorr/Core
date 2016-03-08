<?php

namespace Sectorr\Core\Exceptions;

class ViewNotFoundException extends \Exception
{
    protected $view;

    public function __construct($view)
    {
        parent::__construct();

        $this->view = $view;
        $this->message = 'View ' . $view . ' could not been found.';
    }

    public function getView()
    {
        return $this->view;
    }
}
