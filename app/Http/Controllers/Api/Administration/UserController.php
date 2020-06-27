<?php

namespace App\Http\Controllers\Api\Administration;

use App\Http\Controllers\Helper\TemplateHelper;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\SendEmailHelper;
use App\Models\CUser;
use App\Models\Grooming\EmployeeType;
use App\Models\Grooming\EmployeeTypeEmployee;
use App\Models\Master\BranchOffice;
use App\Models\Master\BranchUser;
use App\Models\Role;
use App\Models\UserRole;
use App\User;
use Exception;
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
        $users = CUser::on('connectionDB')
        ->name(request('name'))
        ->get();

        foreach ($users as $user) {
            # Get user roles
            $roles = Role::on('connectionDB')
            ->select('role.id', 'role.name', 'role.description')
            ->join('user_has_role as ur', 'role.id', 'ur.role_id')
            ->where('ur.user_id', $user->id)
            ->get();

            $employees_types = EmployeeType::on('connectionDB')
            ->select('employee_type.id', 'employee_type.name', 'employee_type.description', 'employee_type.state')
            ->join('employee_type_employee as ete', 'employee_type.id', 'ete.employee_type_id')
            ->where('ete.employee_id', $user->id)
            ->get();

            $validate_role = Role::on('connectionDB')
            ->select('role.id', 'role.name', 'role.description')
            ->join('user_has_role as ur', 'role.id', 'ur.role_id')
            ->where('ur.user_id', $user->id)
            ->whereIn('role.id', [1, 2])
            ->first();

            # Assign roles to json
            if($validate_role){
                $user->employees_types = $employees_types;
            }
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
            'business_days' => 'bail',
            'add_array' => 'bail|array',
            'employee_type_add_array' => 'bail|array',
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
                'business_days' => request('business_days'),
                'phanton_user' => 0,
                'state_id' => 1
            ]);
            # Validate if the user was created
            if($principal_user){

                $branch_user = BranchUser::where('user_id', Auth::id())->first();

                $create_branch_user = BranchUser::create([
                    'user_id' => $principal_user->id,
                    'branch_id' => $branch_user->branch_id
                ]);

                $user = CUser::on('connectionDB')->create([
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
                    $validate_user_has_role = UserRole::on('connectionDB')->where('user_id', $user->id)->where('role_id', $add_array)->first();

                    if(!$validate_user_has_role){
                        $user_has_role = UserRole::on('connectionDB')->create([
                            'user_id' => $user->id,
                            'role_id' => $add_array,
                        ]);
                    }
                }

                foreach (request('employee_type_add_array') as $add_array) {
                    # We need to add the employees type´s id for each record in the list.
                    $employee_type_employee = EmployeeTypeEmployee::on('connectionDB')->where('employee_id', $user->id)->where('employee_type_id', $add_array)->first();

                    if(!$employee_type_employee){
                        $create_employee_type_employee = EmployeeTypeEmployee::on('connectionDB')->create([
                            'employee_id' => $user->id,
                            'employee_type_id' => $add_array,
                        ]);
                    }
                }

                 # Here we will generate a code to verify the email
            while(TRUE){
                # Here we create a code
                $email_code = uniqid(rand(1000, 9999), true);
                $password_code = uniqid(rand(1000, 9999), true);
                # Here we check if there is a User that has the same email verification code
                $code_email_exist = User::where('email_code', $email_code)->first();
                $code_password_exist = User::where('password_code', $password_code)->first();
                # If there is not, we exit the loop
                if (!$code_email_exist && !$code_password_exist){
                    break;
                }
            }

            $data = array(
                'password_code' => $password_code,
                'email_code' => $email_code,
                'name' => $user->name." ".$user->last_name,
                'email' => $user->email,
            );
            # We obtain the user's data to send the mail
            $principal_email = array((object)['email' => $user->email, 'name' => $user->name." ".$user->last_name]);

            #Send email
            $send_email = SendEmailHelper::sendEmail('Correo de verificación de cuenta de GIMED.', TemplateHelper::emailVerify($data), $principal_email, array());
            if($send_email != 1){
                return response()->json(['response' => ['error' => [$send_email]]], 400);
            }

            }else{
                return response()->json(['response' => ['error' => ['Ususario no encontrado']]], 400);
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
        $user = CUser::on('connectionDB')->where('state_id', 1)->find($id);

        # Validate if the user exists
        if(!$user){
            return response()->json(['response' => ['error' => ['Ususario no encontrado']]], 400);
        }

        # Get user roles
        $roles = Role::on('connectionDB')->select('role.id', 'role.name', 'role.description')
        ->join('user_has_role as ur', 'role.id', 'ur.role_id')
        ->where('ur.user_id', $user->id)
        ->get();

        $employees_types = EmployeeType::on('connectionDB')
        ->select('employee_type.id', 'employee_type.name', 'employee_type.description', 'employee_type.state')
        ->join('employee_type_employee as ete', 'employee_type.id', 'ete.employee_type_id')
        ->where('ete.employee_id', $user->id)
        ->get();

        $validate_role = Role::on('connectionDB')
        ->select('role.id', 'role.name', 'role.description')
        ->join('user_has_role as ur', 'role.id', 'ur.role_id')
        ->where('ur.user_id', $user->id)
        ->whereIn('role.id', [1, 2])
        ->first();

        # Assign roles to json
        $user->roles = $roles;

        if($validate_role){
            $user->employees_types = $employees_types;
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
            'email' => 'required|email|max:80',
            'state_id' => 'required|integer',
            'business_days' => 'bail',
            'add_array' => 'bail|array',
            'delete_array' => 'bail|array',
            'employee_type_add_array' => 'bail|array',
            'employee_type_delete_array' => 'bail|array',
        ]);
        if($validator->fails())
        {
          return response()->json(['response' => ['error' => $validator->errors()->all()]],400);
        }

        # Here we get the instance of an user
        $user = User::on('connectionDB')->find($id);

        # Here we check if the user does not exist
        if(!$user){
            return response()->json(['response' => ['error' => ['Ususario no encontrado']]], 400);
        }

        $validate_user_email = User::where('email', request('email'))->where('id', '!=', $user->principal_id)->first();

        if($validate_user_email){
            return response()->json(['response' => ['error' => ['El correo ya se encuentra registrado']]], 400);
        }

        DB::connection('connectionDB')->beginTransaction();
        DB::beginTransaction();
        try{
            # Here we update the company user user data
            $user->name = request('name');
            $user->last_name = request('last_name');
            $user->phone = request('phone');
            $user->address = request('address');
            $user->dni = request('dni');
            $user->email = request('email');
            $user->state_id = request('state_id');
            $user->business_days = request('business_days');

            # Here we get the instance of an principal user
            $principal_user = User::find($user->principal_id);

            # Here we check if the principal user does not exist
            if(!$principal_user){
                return response()->json(['response' => ['error' => ['Ususario no encontrado']]], 400);
            }

            # Here we update the company user user data
            $principal_user->name = request('name');
            $principal_user->last_name = request('last_name');
            $principal_user->phone = request('phone');
            $principal_user->address = request('address');
            $principal_user->dni = request('dni');
            $principal_user->email = request('email');
            $principal_user->state_id = request('state_id');



            foreach (request('delete_array') as $delete_array) {
                # We need to remove the role´s id for each record in the list.
                $validate_user_has_role = UserRole::on('connectionDB')->where('user_id', $user->id)->where('role_id', $delete_array)->first();

                if($validate_user_has_role){
                    $validate_user_has_role->delete();
                }
            }

            # Employee type
            foreach (request('employee_type_delete_array') as $delete_array) {
                # We need to remove the role´s id for each record in the list.
                $validate_employee_type_employee = EmployeeTypeEmployee::on('connectionDB')->where('employee_id', $user->id)->where('employee_type_id', $delete_array)->first();

                if($validate_employee_type_employee){
                    $validate_employee_type_employee->delete();
                }
            }


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

            # Employee type
            foreach (request('employee_type_add_array') as $add_array) {
                # We need to add the employees type´s id for each record in the list.
                $employee_type_employee = EmployeeTypeEmployee::on('connectionDB')->where('employee_id', $user->id)->where('employee_type_id', $add_array)->first();

                if(!$employee_type_employee){
                    $create_employee_type_employee = EmployeeTypeEmployee::on('connectionDB')->create([
                        'employee_id' => $user->id,
                        'employee_type_id' => $add_array,
                    ]);
                }
            }
        }catch(Exception $e){
            DB::connection('connectionDB')->rollback();
            DB::rollback();
            return response()->json( ['response' => ['error' => ['Error'], 'data' => [$e->getMessage(), $e->getFile(), $e->getLine()]]], 400);
        }
        $user->update();
        $principal_user->update();
        # Here we return success.
        DB::connection('connectionDB')->commit();
        DB::commit();
        return response()->json(['response' => 'Usuario actualizado con exito.'], 200);
    }

    public function userProfile(Request $request)
    {
        $user_branch = BranchUser::where('user_id', Auth::id())->first();
        if(!$user_branch){
            return response()->json(['response' => ['error' => ['No perteneces a una empresa']]], 400);
        }

        $branch = BranchOffice::where('id', '!=', 1)->find($user_branch->branch_id);

        if($branch->id != 1){
            $user = CUser::on('connectionDB')->where('state_id', 1)->where('principal_id', Auth::id())->first();
        }else{
            $user = User::on('connectionDB')->where('state_id', 1)->find(Auth::id());
        }

        # Get the user by id

        # Validate if the user exists
        if(!$user){
            return response()->json(['response' => ['error' => ['Ususario no encontrado']]], 400);
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

}
