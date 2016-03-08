<?php

namespace Sectorr\Core\Auth;

use Sectorr\Core\Config;
use Sectorr\Core\Input\Input;

class Auth {

    /**
     * Returns the logged in user's data as an object.
     *
     * @return bool|object
     */
    public static function user()
    {
        if (isset($_SESSION['user'])) {
            return (object) $_SESSION['user'];
        }
        return false;
    }

    /**
     * Attempt to login the user.
     * If succeeds, create a user session.
     *
     * @param $input
     * @return bool
     */
    public static function attempt($input)
    {
        $userModel = Config::get('user');
        $model = new $userModel();

        $count = 0;

        foreach($input as $field => $value) {
            if($field == '_') {
                unset($input[$field]);
            } else {
                $count++;
            }
            if($count == 1) {
                $primary = $field;
            }
        }

        if(!empty($input)) {
            $user = $model->where($primary, $input[$primary])->first();

            if(! empty($user) && $user['password'] == Input::get('password')) {
                $_SESSION['user'] = $user;
                return true;
            }

            return false;
        }
    }

    /**
     * Check if the user is logged in.
     *
     * @return bool
     */
    public static function check()
    {
        if(isset($_SESSION['user'])) {
            return true;
        }
        return false;
    }

    /**
     * Unset the user session.
     *
     * @return bool
     */
    public static function logout()
    {
        if(self::check()) {
            session_unset($_SESSION['user']);
            return true;
        }
        return false;
    }
}