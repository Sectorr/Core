<?php

namespace Sectorr\Core\Http;

class Session
{

    /**
     * Set a session using given key and value.
     *
     * @param $key
     * @param $value
     */
    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Check if session with given key exists.
     *
     * @param $key
     * @return bool
     */
    public static function has($key)
    {
        return ! empty($_SESSION[$key]);
    }

    /**
     * Return session with given key.
     *
     * @param $key
     * @return mixed
     */
    public static function get($key)
    {
        if (self::has($key)) {
            return $_SESSION[$key];
        }
    }
}
