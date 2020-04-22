<?php

namespace App\Http\Controllers\Api\Administration;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\UserRole;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{

    public function __construct()
    {
        # the middleware param 1 = List user
        $this->middleware('permission:/list_user')->only(['show', 'index']);
        # the middleware param 2 = Create user
        $this->middleware('permission:/create_user')->only('store');
        # the middleware param 3 = Update user
        $this->middleware('permission:/update_user')->only(['update', 'destroy']);

        $this->middleware('set_connection');

    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        # Get users
        $users = User::on('connectionDB')->get();

        foreach ($users as $user) {
            # Get user roles
            $roles = Role::on('connectionDB')
            ->select('role.id', 'role.name', 'role.description')
            ->join('user_has_role as ur', 'role.id', 'ur.role_id')
            ->where('ur.user_id', $user->id)
            ->get();

            # Assign roles to json
            $user->roles = $roles;

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
        ]);
        if($validator->fails())
        {
          return response()->json(['response' => ['error' => $validator->errors()->all()]],400);
        }

        DB::connection('connectionDB')->beginTransaction();
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

                $user = 0;

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
            DB::connection('connectionDB')->rollback();
            DB::rollback();
        }
        DB::connection('connectionDB')->commit();
        DB::commit();
        return response()->json(['response' => 'Success'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        # Get the user by id
        $user = User::on('connectionDB')->where('state_id', 1)->find($id);

        # Validate if the user exists
        if(!$user){
            return response()->json(['response' => ['error' => ['Ususario no encontrado']]], 404);
        }

        # Get user roles
        $roles = Role::on('connectionDB')->select('role.id', 'role.name', 'role.description')
        ->join('user_has_role as ur', 'role.id', 'ur.role_id')
        ->where('ur.user_id', $user->id)
        ->get();

        # Assign roles to json
        $user->roles = $roles;

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
            'name' => 'bail|required|min:2|max:50',
            'email' => 'bail|required|min:5|max:75|unique:users,email,'.$id,
            'add_array' => 'bail|array',
            'delete_array' => 'bail|array',
        ]);
        if($validator->fails())
        {
          return response()->json(['response' => ['error' => $validator->errors()->all()]],400);
        }

        # Here we get the instance of an user
        $user = User::on('connectionDB')->find($id);

        # Here we check if the user does not exist
        if(!$user){
            return response()->json(['response' => ['error' => ['Ususario no encontrado']]], 404);
        }

        # Here we update the basic user data
        $user->name = request('name');
        $user->email = request('email');

        DB::connection('connectionDB')->beginTransaction();
        try{
            if(count(request('delete_array')) > 0){
                foreach (request('delete_array') as $delete_array) {
                    # We need to remove the role´s id for each record in the list.
                    $validate_user_has_role = UserRole::on('connectionDB')->where('user_id', $user->id)->where('role_id', $delete_array)->first();

                    if($validate_user_has_role){
                        $validate_user_has_role->delete();
                    }
                }
            }

            if(count(request('add_array')) > 0){
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
            }
        }catch(Exception $e){
            DB::connection('connectionDB')->rollback();
            return response()->json( ['response' => ['error' => ['Error al asignar rol'], 'data' => [$e->getMessage(), $e->getFile(), $e->getLine()]]], 400);
        }
        $user->update();
        # Here we return success.
        DB::connection('connectionDB')->commit();
        return response()->json(['response' => 'Usuario actualizado con exito.'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::on('connectionDB')->where('id', '!=', Auth::id())->find($id);

        if(!$user){
            return response()->json(['response' => ['error' => ['Ususario no encontrado']]], 404);
        }
        if($user->state_id == 1){
            $user->state_id = 2;
        }else if($user->state_id == 2) {
            $user->state_id = 1;
        }
        $user->update();

        return response()->json(['response' => 'Success'], 200);
    }
}
