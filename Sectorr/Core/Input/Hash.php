<?php

namespace Sectorr\Core\Input;

class Hash
{
    const ALGORITHM = PASSWORD_DEFAULT;

    /**
     * Returns given input hashed.
     * 
     * @param $input
     * @return bool|string
     */
    public static function make($input)
    {
        return password_hash($input, self::ALGORITHM);
    }

    /**
     * Checks if input matches the hashed value
     * 
     * @param $input
     * @param $password
     * @return bool
     */
    public static function check($input, $password)
    {
        return password_verify($input, $password);
    }
}