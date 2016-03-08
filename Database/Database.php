<?php

namespace Sectorr\Core\Database;

use Sectorr\Core\Config;
use medoo;

class Database extends medoo
{

    /**
     * Database constructor.
     */
    public function __construct()
    {
        $dbConfig = Config::get('database');
        parent::__construct([
            'database_type' => 'mysql',
            'database_name' => $dbConfig['name'],
            'server' => $dbConfig['host'],
            'username' => $dbConfig['username'],
            'password' => $dbConfig['password'],
            'charset' => 'utf8'
        ]);
    }
}
