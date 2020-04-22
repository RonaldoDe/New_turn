<?php

namespace App\Http\Controllers\Helper;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NewDatabaseHelper extends Controller
{
    public static function newBarberShopDatabase($db_name, $data)
    {
        $db_company = DB::statement("CREATE DATABASE $db_name");
        if($db_company){

            $configDb = [
                'driver'      => 'mysql',
                'host'        => env('DB_HOST', '127.0.0.1'),
                'port'        => env('DB_PORT', '3306'),
                'database'    => $db_name,
                'username'    => env('DB_USERNAME', 'forge'),
                'password'    => env('DB_PASSWORD', ''),
                'unix_socket' => env('DB_SOCKET', ''),
                'charset'     => 'utf8',
                'collation'   => 'utf8_unicode_ci',
                'prefix'      => '',
                'strict'      => true,
                'engine'      => null,
            ];

            \Config::set('database.connections.newCompany', $configDb);

        }
    }
}
