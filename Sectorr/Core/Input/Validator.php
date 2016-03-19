<?php

namespace Sectorr\Core\Input;

use Sectorr\Core\Database\Database;

class Validator
{

    protected $db;
    protected $errors = [];
    protected $failed = false;
    const RULE_SEPERATOR = "|";
    const ARGUMENT_SEPERATOR = ":";

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
    public function passes($input, $rules)
    {
        foreach ($rules as $field => $rule) {
            $this->callValidator($input[$field], $field, $rule);
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
            var_dump("error: {$field} is a required field");
            //$this->errors[] = "{$field} is a required field";
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
            var_dump("error: {$input} not a valid email");
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
            var_dump("error: {$input} not a valid password");
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
            var_dump("error: {$column} not unique");
            $this->failed = true;
        }
    }

    /**
     * Retrieves multiple arguments from argument string.
     *
     * @param $arg
     * @return array|bool
     */
    protected function multiple($value)
    {
        $arguments = explode(':', $value);

        if (count($arguments) > 1) {
            $rule = $arguments[0];
            unset($arguments[0]);

            return ['multiple' => true, 'rule' => $rule, 'arguments' => array_values($arguments)];
        }

        return ['multiple' => false];
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
        $arguments = explode('|', $arguments);

        foreach ($arguments as $arg) {
            if ($this->multiple($arg)['multiple']) {
                switch ($this->multiple($arg)['rule']) {
                    case 'unique':
                        $table = $this->multiple($arg)['arguments'][0];
                        $column = $this->multiple($arg)['arguments'][1];
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
