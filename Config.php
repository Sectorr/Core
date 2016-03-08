<?php

namespace Sectorr\Core;

use Sectorr\Core\Exceptions\ConfigItemNotSetException;

class Config
{
    /**
     * Get Config item by key.
     *
     * @param $key
     * @return mixed
     * @throws ConfigItemNotSetException
     */
    public static function get($key)
    {
        $config = json_decode(file_get_contents(PATH . '/app/config.json'), true);

        if(! array_key_exists($key, $config)) {
            throw new ConfigItemNotSetException($key);
        }

        return $config[$key];
    }
}
