<?php

namespace Sectorr\Core\Input;

class Input
{

    /**
     * Retrieve all input.
     *
     * @return array
     */
    public static function all()
    {
        return (array) $_REQUEST;
    }

    /**
     * Check the given request type exists.
     *
     * @param string $type
     * @return bool
     */
    public static function exists($type = 'post')
    {
        switch ($type) {
            case 'post':
                return (!empty($_POST)) ? true : false;
                break;
            case 'get':
                return (!empty($_GET)) ? true : false;
                break;
            default:
                return false;
                break;
        }
    }

    /**
     * Returns the given input item.
     *
     * @param $item
     * @return string
     */
    public static function get($item)
    {
        if (isset($_POST[$item])) {
            return $_POST[$item];
        } elseif (isset($_GET[$item])) {
            return $_GET[$item];
        } else {
            return '';
        }
    }
}
