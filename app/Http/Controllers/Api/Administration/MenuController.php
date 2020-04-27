<?php

namespace App\Http\Controllers\Api\Administration;

use App\Http\Controllers\Controller;
use App\Models\CUser;
use App\Models\Master\BranchUser;
use App\Models\Module;
use App\Models\Permission;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MenuController extends Controller
{
    public function __construct()
    {
        $this->middleware('set_connection');
    }
    public function modulegeneral(Request $request)
    {
        # Get user
        $user = User::find(Auth::id());
        $data = [
            'phanton' => 0,
            'master' => 0
        ];

        # Validate if the user exists
        if(!$user){
            return response()->json(['response' => ['error' => ['Usuario no encontrado']]], 404);
        }

        $branch_user = BranchUser::where('user_id', $user->id)->first();
        if(!$branch_user){
            return response()->json(['response' => 'Free-user'], 200);
        }
        if($branch_user->branch_id == 1){
            $user_id = $user->id;
            $data['master'] = 1;
        }else{
            $c_user = CUser::on('connectionDB')->where('principal_id', $user->id)->first();
            $user_id = $c_user->id;
            if($c_user->phanton_user){
                $data['phanton'] = 1;
            }
        }

        # Get the modules with their respective permissions
        $module_list = Module::on('connectionDB')->select('module.id as module_id', 'module.name as module_name', 'p.id as permission_id', 'p.name as permission_name')
        ->join('permission as p', 'module.id', 'p.module_id')
        ->join('role_has_permission as rp', 'p.id', 'rp.permission_id')
        ->join('role as r', 'rp.role_id', 'r.id')
        ->join('user_has_role as ur', 'r.id', 'ur.role_id')
        ->join('users as u', 'ur.user_id', 'u.id')
        ->distinct('u.id')
        ->where('u.id', $user_id)
        ->get();

        # Order by the module name
        $collection = collect($module_list)->sortBy('module_name');
        # Group by the module name
        $modules = collect($collection)->groupBy('module_name');

        return response()->json(['response' => $modules, 'master' => $data['master'], 'phantom' => $data['phanton']], 200);
    }
}
