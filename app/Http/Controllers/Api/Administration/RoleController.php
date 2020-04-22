<?php

namespace App\Http\Controllers\Api\Administration;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{

    public function __construct()
    {
        # the middleware list permission
        $this->middleware('permission:/list_role')->only(['show', 'index']);
        # the middleware create, edit, delete role
        $this->middleware('permission:/create_role')->only(['store', 'update', 'destroy']);

        $this->middleware('set_connection');

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        # Get roles
        $roles = Role::on('connectionDB')->get();

        foreach ($roles as $role) {
            $permissions = Permission::on('connectionDB')
            ->select('permission.id', 'permission.name', 'permission.description')
            ->join('role_has_permission as rp', 'permission.id', 'rp.permission_id')
            ->where('rp.role_id', $role->id)
            ->get();

            $role->permissions = $permissions;
        }

        return response()->json(['response' => $roles], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator=\Validator::make($request->all(),[
            'name' => 'required|min:1|max:75',
            'description' => 'required',
            'add_array' => 'bail|array',
            'add_array.*' => 'bail|integer',
        ]);
        if($validator->fails())
        {
          return response()->json(['response' => ['error' => $validator->errors()->all()]],400);
        }

        # Create a role
        $role = Role::on('connectionDB')->create([
            'name' => request('name'),
            'description' => request('description')
        ]);

        DB::on('connectionDB')->beginTransaction();
        try{
            foreach (request('add_array') as $add_array) {
                # We need to add the permissions id for each record in the list.
                $validate_role_permission = RolePermission::on('connectionDB')->where('role_id', $role->id)->where('permission_id', $add_array)->first();

                if(!$validate_role_permission){
                    $role_has_permission = RolePermission::on('connectionDB')->create([
                        'role_id' => $role->id,
                        'permission_id' => $add_array,
                    ]);
                }
            }

        }catch(Exception $e){
            DB::on('connectionDB')->rollback();
            return response()->json( ['response' => ['error' => ['Error al agregar permisos al rol'], 'data' => [$e->getMessage(), $e->getFile(), $e->getLine()]]], 400);
        }
        # Here we return success.
        DB::commit();

        return response()->json(['response' => 'Rol creado con exito.'], 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        # Get role by id
        $role = Role::on('connectionDB')->find($id);

        $permissions = Permission::on('connectionDB')
        ->select('permission.id', 'permission.name', 'permission.description')
        ->join('role_has_permission as rp', 'permission.id', 'rp.permission_id')
        ->where('rp.role_id', $role->id)
        ->get();

        $role->permissions = $permissions;


        return response()->json(['response' => $role], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator=\Validator::make($request->all(),[
            'name' => 'required|min:1|max:75',
            'description' => 'required',
            'add_array' => 'bail|array',
            'delete_array' => 'bail|array',
        ]);
        if($validator->fails())
        {
          return response()->json(['response' => ['error' => $validator->errors()->all()]],400);
        }

        # Get role
        $role = Role::on('connectionDB')->find($id);

        # Validate if the role exists
        if(!$role){
            return response()->json(['response' => ['error' => ['Rol no encontrado.']]], 404);
        }

        # Set the data to update
        $role->name = request('name');
        $role->description = request('description');

        DB::on('connectionDB')->beginTransaction();
        try{
            foreach (request('delete_array') as $delete_array) {
                # We need to remove the permissions id for each record in the list.
                $validate_role_permission = RolePermission::on('connectionDB')->where('role_id', $role->id)->where('permission_id', $delete_array)->first();

                if($validate_role_permission){
                    $validate_role_permission->delete();
                }
            }

            foreach (request('add_array') as $add_array) {
                # We need to add the permissions id for each record in the list.
                $validate_role_permission = RolePermission::on('connectionDB')->where('role_id', $role->id)->where('permission_id', $add_array)->first();

                if(!$validate_role_permission){
                    $role_has_permission = RolePermission::on('connectionDB')->create([
                        'role_id' => $role->id,
                        'permission_id' => $add_array,
                    ]);
                }
            }
        }catch(Exception $e){
            DB::on('connectionDB')->rollback();
            return response()->json( ['response' => ['error' => ['Error al agregar permisos al rol'], 'data' => [$e->getMessage(), $e->getFile(), $e->getLine()]]], 400);
        }
        # Here we return success.
        DB::on('connectionDB')->commit();
        # Update
        $role->update();

        return response()->json(['response' => 'Rol creado con exito.'], 200);
    }

}
