<?php

namespace Sectorr\Core\Input;

use Sectorr\Core\Database\Database;

class Validator
{

    protected $db;
    protected $errors = [];
    protected $failed = false;

    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * Loops through the rules and checks the current input.
     *
     * @param $input
     * @param $rules
     * @return bool
     */
    public function validate($input, $rules)
    {
        foreach ($rules as $field => $arguments) {
            $this->callValidator($input[$field], $field, $arguments);
        }
        return ! $this->failed;
    }

    /**
     * Check if the given input isn't empty.
     *
     * @param $field
     * @param $input
     */
    protected function required($field, $input)
    {
        if (empty($input)) {
            $this->failed = true;
            $this->errors[] = "{$field} is a required field";
        }
    }

    /**
     * Check if the given input is a valid email adress.
     *
     * @param $input
     */
    protected function email($input)
    {
        if (!filter_var($input, FILTER_VALIDATE_EMAIL)) {
            $this->failed = true;
        }
    }

    /**
     * Check if the given input is a valid password.
     *
     * @param $input
     */
    protected function password($input)
    {
        if (!(strlen($input) >= 4)) {
            $this->failed = true;
        }
    }

    /**
     * Check if the given input is unique in the given database table and column.
     *
     * @param $input
     * @param $table
     * @param $column
     */
    protected function unique($input, $table, $column)
    {
        $email = $this->db->get($table, $column, [
            $column => $input
        ]);
        if ($email) {
            $this->failed = true;
        }
    }

    /**
     * Retrieves multiple arguments from argument string.
     *
     * @param $arg
     * @return array|bool
     */
    protected function multiple($arg)
    {
        if (count(explode('|', $arg)) > 1) {
            $args = explode('|', $arg);
            if (count(explode(':', $args[1]))) {
                $a       = $args[0];
                $specs = explode(':', $args[1]);
                return [true, $a, $specs];
            }
        } else {
            return false;
        }
    }

    /**
     * Loops through all arguments and checks what methods need to be called.
     *
     * @param $input
     * @param $field
     * @param $arguments
     */
    protected function callValidator($input, $field, $arguments)
    {
        foreach ($arguments as $arg) {
            if ($this->multiple($arg)) {
                $argument = $this->multiple($arg)[1];
                switch ($argument) {
                    case 'unique':
                        $table = $this->multiple($arg)[2][0];
                        $column = $this->multiple($arg)[2][1];
                        $this->unique($input, $table, $column);
                        break;
                }
            } else {
                switch ($arg) {
                    case 'required':
                        $this->required($field, $input);
                        break;
                    case 'email':
                        $this->email($input);
                        break;
                    case 'password':
                        $this->password($input);
                        break;
                }
            }
        }
    }

    // @TODO: find a way to pass errors when validator doesn't pass.
    public function errors()
    {
        return $this->errors;
    }
}
