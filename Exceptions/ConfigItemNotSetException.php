<?php

namespace Sectorr\Core\Exceptions;

class ConfigItemNotSetException extends \Exception
{
    protected $configItem;

    public function __construct($configItem)
    {
        parent::__construct();

        $this->configItem = $configItem;
        $this->message = 'Config item ' . $configItem . ' has not been set in config file.';
    }

    public function getConfigItem()
    {
        return $this->configItem;
    }
}