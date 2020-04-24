<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\SetConnectionHelper;
use App\Models\CUser;
use App\Models\Master\BranchOffice;
use App\Models\Master\BranchUser;
use App\Models\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MUsersController extends Controller
{

    public function __construct()
    {
        # the middleware param 1 = List user
        $this->middleware('permission:/list_user')->only(['show', 'index']);
        # the middleware param 2 = Create user
        $this->middleware('permission:/create_user')->only('store');
        # the middleware param 3 = Update user
        $this->middleware('permission:/update_user')->only(['update', 'destroy']);
    }

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
            # Get users
            $users = User::get();

            foreach ($users as $user) {
                # Get user roles
                $roles = Role::select('role.id', 'role.name', 'role.description')
                ->join('user_has_role as ur', 'role.id', 'ur.role_id')
                ->where('ur.user_id', $user->id)
                ->get();

                # Assign roles to json
                $user->roles = $roles;
            }

        }else{
            # --------------------- Set connection ------------------------------------#
            $branch = BranchOffice::where('id', '!=', 1)->find(request('branch_id'));

            if(!$branch){
                return response()->json(['response' => ['error' => ['Sucursal no encontrada']]], 404);
            }

            $set_connection = SetConnectionHelper::setByDBName($branch->db_name);
            # --------------------- Set connection ------------------------------------#

            # Get users
            $users = User::on($branch->db_name)->get();

            foreach ($users as $user) {
                # Get user roles
                $roles = Role::on($branch->db_name)->select('role.id', 'role.name', 'role.description')
                ->join('user_has_role as ur', 'role.id', 'ur.role_id')
                ->where('ur.user_id', $user->id)
                ->get();

                # Assign roles to json
                $user->roles = $roles;

            }
        }

        return response()->json(['response' => $users], 200);
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
            'name' => 'required|max:20',
            'last_name' => 'required|max:20',
            'phone' => 'required|max:20',
            'address' => 'required|max:20',
            'dni' => 'required|max:20',
            'email' => 'required|email|max:80|email|unique:users',
            'password' => 'required|max:50|min:6',
            'add_array' => 'bail|array',
            'branch_id' => 'integer|exists:branch_office,id',
        ]);
        if($validator->fails())
        {
          return response()->json(['response' => ['error' => $validator->errors()->all()]],400);
        }

        if(!empty(request('branch_id'))){

            # --------------------- Set connection ------------------------------------#
            $branch = BranchOffice::where('id', '!=', 1)->find(request('branch_id'));

            if(!$branch){
                return response()->json(['response' => ['error' => ['Sucursal no encontrada']]], 404);
            }

            $set_connection = SetConnectionHelper::setByDBName($branch->db_name);
            # --------------------- Set connection ------------------------------------#
            DB::connection($branch->db_name)->beginTransaction();
            DB::beginTransaction();
            try{
                # Create user
                $principal_user = User::create([
                    'name' => request('name'),
                    'last_name' => request('last_name'),
                    'phone' => request('phone'),
                    'address' => request('address'),
                    'dni' => request('dni'),
                    'email' => request('email'),
                    'password' => bcrypt(request('password')),
                    'phanton_user' => 0,
                    'state_id' => 1
                ]);
                # Validate if the user was created
                if($principal_user){




                    $branch_user = BranchUser::create([
                        'user_id' => $principal_user->id,
                        'branch_id' => request('branch_id')
                    ]);


                    $user = CUser::on($branch->db_name)->create([
                        'name' => request('name'),
                        'last_name' => request('last_name'),
                        'phone' => request('phone'),
                        'address' => request('address'),
                        'dni' => request('dni'),
                        'email' => request('email'),
                        'phanton_user' => 0,
                        'principal_id' => $principal_user->id,
                        'state_id' => 1
                    ]);


                    foreach (request('add_array') as $add_array) {
                        # We need to add the role´s id for each record in the list.
                        $validate_user_has_role = UserRole::on($branch->db_name)->where('user_id', $user->id)->where('role_id', $add_array)->first();

                        if(!$validate_user_has_role){
                            $user_has_role = UserRole::on($branch->db_name)->create([
                                'user_id' => $user->id,
                                'role_id' => $add_array,
                            ]);
                        }
                    }

                }else{
                    return response()->json(['response' => ['error' => ['Ususario no encontrado']]], 404);
                }
            }catch(Exception $e){
                DB::connection($branch->db_name)->rollback();
                DB::rollback();
            }
            DB::connection($branch->db_name)->commit();
            DB::commit();
            return response()->json(['response' => 'Success'], 200);
        }else{
            DB::beginTransaction();
            try{
                # Create user
                $user = User::create([
                    'name' => request('name'),
                    'last_name' => request('last_name'),
                    'phone' => request('phone'),
                    'address' => request('address'),
                    'dni' => request('dni'),
                    'email' => request('email'),
                    'password' => bcrypt(request('password')),
                    'phanton_user' => 0,
                    'state_id' => 1
                ]);
                # Validate if the user was created
                if($user){

                    foreach (request('add_array') as $add_array) {
                        # We need to add the role´s id for each record in the list.
                        $validate_user_has_role = UserRole::on('connectionDB')->where('user_id', $user->id)->where('role_id', $add_array)->first();

                        if(!$validate_user_has_role){
                            $user_has_role = UserRole::on('connectionDB')->create([
                                'user_id' => $user->id,
                                'role_id' => $add_array,
                            ]);
                        }
                    }

                }else{
                    return response()->json(['response' => ['error' => ['Ususario no encontrado']]], 404);
                }
            }catch(Exception $e){
                DB::rollback();
            }
            DB::commit();
            return response()->json(['response' => 'Success'], 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $validator=\Validator::make($request->all(),[
            'branch_id' => 'integer|exists:branch_office,id',
        ]);
        if($validator->fails())
        {
          return response()->json(['response' => ['error' => $validator->errors()->all()]],400);
        }

        if(empty(request('branch_id'))){
            # Get users
            $user = User::find($id);

            # Get user roles
            $roles = Role::select('role.id', 'role.name', 'role.description')
            ->join('user_has_role as ur', 'role.id', 'ur.role_id')
            ->where('ur.user_id', $user->id)
            ->get();

            # Assign roles to json
            $user->roles = $roles;

        }else{
            # --------------------- Set connection ------------------------------------#
            $branch = BranchOffice::where('id', '!=', 1)->find(request('branch_id'));

            if(!$branch){
                return response()->json(['response' => ['error' => ['Sucursal no encontrada']]], 404);
            }

            $set_connection = SetConnectionHelper::setByDBName($branch->db_name);
            # --------------------- Set connection ------------------------------------#

            # Get users
            $user = User::on($branch->db_name)->find($id);

            # Get user roles
            $roles = Role::on($branch->db_name)->select('role.id', 'role.name', 'role.description')
            ->join('user_has_role as ur', 'role.id', 'ur.role_id')
            ->where('ur.user_id', $user->id)
            ->get();

            # Assign roles to json
            $user->roles = $roles;

        }

        return response()->json(['response' => $user], 200);
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
            'name' => 'required|max:20',
            'last_name' => 'required|max:20',
            'phone' => 'required|max:20',
            'address' => 'required|max:20',
            'dni' => 'required|max:20',
            'email' => 'required|email|max:80|email',
            'state_id' => 'required|integer',
            'add_array' => 'bail|array',
            'delete_array' => 'bail|array',
            'branch_id' => 'integer|exists:branch_office,id',
        ]);
        if($validator->fails())
        {
          return response()->json(['response' => ['error' => $validator->errors()->all()]],400);
        }

        if(!empty(request('branch_id'))){

            # --------------------- Set connection ------------------------------------#
            $branch = BranchOffice::where('id', '!=', 1)->find(request('branch_id'));

            if(!$branch){
                return response()->json(['response' => ['error' => ['Sucursal no encontrada']]], 404);
            }

            $set_connection = SetConnectionHelper::setByDBName($branch->db_name);
            # --------------------- Set connection ------------------------------------#

            # Here we get the instance of an user
            $user = CUser::on($branch->db_name)->find($id);
            # Here we check if the user does not exist
            if(!$user){
                return response()->json(['response' => ['error' => ['Ususario no encontrado']]], 404);
            }

            DB::beginTransaction();
            DB::connection($branch->db_name)->beginTransaction();
            try{
                # Here we update the basic user data
                $user->name = request('name');
                $user->last_name = request('last_name');
                $user->phone = request('phone');
                $user->address = request('address');
                $user->dni = request('dni');
                $user->email = request('email');
                $user->state_id = request('state_id');

                # Here we get the instance of an user
                $principal_user = User::find($user->principal_id);

                # Here we check if the user does not exist
                if(!$principal_user){
                    return response()->json(['response' => ['error' => ['Ususario no encontrado']]], 404);
                }

                # Here we update the basic user data
                $principal_user->name = request('name');
                $principal_user->last_name = request('last_name');
                $principal_user->phone = request('phone');
                $principal_user->address = request('address');
                $principal_user->dni = request('dni');

                $validate_email = User::where('email', request('email'))->where('id', '!=', $principal_user->id)->first();
                if($validate_email){
                    return response()->json(['response' => ['error' => ['El correo ya existe']]], 400);
                }

                $principal_user->email = request('email');
                $principal_user->state_id = request('state_id');


                foreach (request('delete_array') as $delete_array) {
                    # We need to remove the role´s id for each record in the list.
                    $validate_user_has_role = UserRole::on($branch->db_name)->where('user_id', $user->id)->where('role_id', $delete_array)->first();

                    if($validate_user_has_role){
                        $validate_user_has_role->delete();
                    }
                }

                foreach (request('add_array') as $add_array) {
                    # We need to add the role´s id for each record in the list.
                    $validate_user_has_role = UserRole::on($branch->db_name)->where('user_id', $user->id)->where('role_id', $add_array)->first();

                    if(!$validate_user_has_role){
                        $user_has_role = UserRole::on($branch->db_name)->create([
                            'user_id' => $user->id,
                            'role_id' => $add_array,
                        ]);
                    }
                }
            }catch(Exception $e){
                DB::rollback();
                DB::connection($branch->db_name)->rollback();
                return response()->json( ['response' => ['error' => ['Error al asignar rol'], 'data' => [$e->getMessage(), $e->getFile(), $e->getLine()]]], 400);
            }
            $principal_user->update();
            $user->update();
            # Here we return success.
            DB::commit();
            DB::connection($branch->db_name)->commit();
            return response()->json(['response' => 'Usuario actualizado con exito.'], 200);

        }else{
            # Here we get the instance of an user
            $user = User::find($id);

            # Here we check if the user does not exist
            if(!$user){
                return response()->json(['response' => ['error' => ['Ususario no encontrado']]], 404);
            }


            DB::beginTransaction();
            try{
                # Here we update the basic user data
                $user->name = request('name');
                $user->last_name = request('last_name');
                $user->phone = request('phone');
                $user->address = request('address');
                $user->dni = request('dni');
                $user->email = request('email');

                foreach (request('delete_array') as $delete_array) {
                    # We need to remove the role´s id for each record in the list.
                    $validate_user_has_role = UserRole::where('user_id', $user->id)->where('role_id', $delete_array)->first();

                    if($validate_user_has_role){
                        $validate_user_has_role->delete();
                    }
                }

                foreach (request('add_array') as $add_array) {
                    # We need to add the role´s id for each record in the list.
                    $validate_user_has_role = UserRole::where('user_id', $user->id)->where('role_id', $add_array)->first();

                    if(!$validate_user_has_role){
                        $user_has_role = UserRole::create([
                            'user_id' => $user->id,
                            'role_id' => $add_array,
                        ]);
                    }
                }
            }catch(Exception $e){
                DB::rollback();
                return response()->json( ['response' => ['error' => ['Error al asignar rol'], 'data' => [$e->getMessage(), $e->getFile(), $e->getLine()]]], 400);
            }
            $user->update();
            # Here we return success.
            DB::commit();
            return response()->json(['response' => 'Usuario actualizado con exito.'], 200);
        }
    }

}
