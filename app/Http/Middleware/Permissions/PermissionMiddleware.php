<?php

namespace App\Http\Middleware\Permissions;

use App\Models\Permission;
use App\Models\Master\BranchUser;
use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $permission)
    {
        # Get user by id
        $user = User::find(Auth::id());

        # Validate if the user exists
        if(!$user){
            return response()->json(['response' => ['error' => ['Usuario no encontrado']]], 404);
        }

        $branch = BranchUser::select('bo.id', 'bo.db_name')
        ->join('branch_office as bo', 'branch_user.branch_id', 'bo.id')
        ->where('branch_user.user_id', $user->id)
        ->first();

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


        # Validate if the user's roles have the requested permission
        $validate_permission = Permission::on('connectionDB')->select('permission.id')
        ->join('role_has_permission as rp', 'permission.id', 'rp.permission_id')
        ->join('role as r', 'rp.role_id', 'r.id')
        ->join('user_has_role as ur', 'r.id', 'ur.role_id')
        ->join('users as u', 'ur.user_id', 'u.id')
        ->where('u.id', $user->id)
        ->where('permission.route', $permission)
        ->first();

        # Validate if you do not have permission
        if(!$validate_permission){
            return response()->json(['response' => ['error' => ['No tienes permiso']]], 403);
        }

        return $next($request);
    }
}
