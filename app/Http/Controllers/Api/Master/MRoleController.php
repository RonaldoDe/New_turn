<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\SetConnectionHelper;
use App\Models\Master\BranchOffice;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

class MRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $validator=\Validator::make($request->all(),[
            'branch_id' => 'integer|exists:branch_office,id',
        ]);
        if($validator->fails())
        {
          return response()->json(['response' => ['error' => $validator->errors()->all()]],400);
        }
        if(empty(request('branch_id'))){

            # Get roles
            $roles = Role::get();

            foreach ($roles as $role) {
                $permissions = Permission::select('permission.id', 'permission.name', 'permission.description')
                ->join('role_has_permission as rp', 'permission.id', 'rp.permission_id')
                ->where('rp.role_id', $role->id)
                ->get();

                $role->permissions = $permissions;
            }

        }else{
            # --------------------- Set connection ------------------------------------#
            $branch = BranchOffice::where('id', '!=', 1)->find(request('branch_id'));

            if(!$branch){
                return response()->json(['response' => ['error' => ['Sucursal no encontrada']]], 400);
            }

            $set_connection = SetConnectionHelper::setByDBName($branch->db_name);
            # --------------------- Set connection ------------------------------------#

            # Get roles
            $roles = Role::on($branch->db_name)->get();

            foreach ($roles as $role) {
                $permissions = Permission::on($branch->db_name)
                ->select('permission.id', 'permission.name', 'permission.description')
                ->join('role_has_permission as rp', 'permission.id', 'rp.permission_id')
                ->where('rp.role_id', $role->id)
                ->get();

                $role->permissions = $permissions;
            }
        }


        return response()->json(['response' => $roles], 200);
    }

}
