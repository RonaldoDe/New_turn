<?php

namespace App\Http\Middleware\Connections;

use App\Models\Master\BranchOffice;
use App\Models\Master\BranchUser;
use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class CheckConnectionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $user = User::find(Auth::id());

        $branch = BranchUser::select('bo.id', 'bo.db_name')
        ->join('branch_office as bo', 'branch_user.branch_id', 'bo.id')
        ->where('branch_user.user_id', $user->id)
        ->first();

        if(!$branch){
            return response()->json(['response' => ['error' => ['Usted no pertenece a una empresa.']]], 400);
        }

        $configDb = [
            'driver'      => 'mysql',
            'host'        => env('DB_HOST', '127.0.0.1'),
            'port'        => env('DB_PORT', '3306'),
            'database'    => $branch->db_name,
            'username'    => env('DB_USERNAME', 'forge'),
            'password'    => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset'     => 'utf8',
            'collation'   => 'utf8_unicode_ci',
            'prefix'      => '',
            'strict'      => false,
            'engine'      => null,
    ];

    \Config::set('database.connections.connectionDB', $configDb);

    return $next($request);
    }
}
