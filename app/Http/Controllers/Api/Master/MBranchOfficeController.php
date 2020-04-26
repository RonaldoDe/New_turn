<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\NewDatabaseHelper;
use App\Http\Controllers\Helper\SetConnectionHelper;
use App\Models\CUser;
use App\Models\Master\BranchOffice;
use App\Models\Master\BranchUser;
use App\Models\UserRole;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MBranchOfficeController extends Controller
{

    public function __construct()
    {
        # the middleware list branches
        $this->middleware('permission:/list_company')->only(['show', 'index']);
        # the middleware create, edit, delete branches
        $this->middleware('permission:/create_company')->only(['store', 'update', 'destroy']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $branches = BranchOffice::select('branch_office.id', 'branch_office.name', 'branch_office.description', 'branch_office.nit', 'branch_office.email', 'branch_office.city', 'branch_office.longitude', 'branch_office.latitude', 'branch_office.address', 'branch_office.phone', 'branch_office.close', 'branch_office.hours_24', 'branch_office.state_id', 'branch_office.company_id', 'c.name as company_name', 'c.description as company_description', 'c.nit as company_nit')
        ->join('company as c', 'branch_office.company_id', 'c.id')
        ->where('branch_office.id', '!=', 1)
        ->get();

        return response()->json(['response' => $branches], 200);
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
            'name' => 'required|min:1|max:125',
            'description' => 'required',
            'nit' => 'required|min:1|max:30',
            'email' => 'required|email|unique:users,email',
            'city' => 'required',
            'longitude' => 'required',
            'latitude' => 'required',
            'address' => 'required',
            'phone' => 'required|min:7|max:10',
            'close' => 'required|integer',
            'hours_24' => 'required|integer',
            'state_id' => 'required|numeric|exists:branch_state,id',
            'company_id' => 'required|numeric|exists:company,id'
        ]);
        if($validator->fails())
        {
          return response()->json(['response' => ['error' => $validator->errors()->all()]],400);
        }

        # Here we will generate a random code for db name
        while(TRUE){
            $lower = strtolower(request('name'));
            $replace = str_replace(' ', '_', $lower);
            $db_name = $replace.'_'.rand(1000, 99999);
            $name_exists = BranchOffice::where('db_name', $db_name)->first();
            # If there is not, we exit the loop
            if (!$name_exists){
                break;
            }
        }

        DB::beginTransaction();
        try{
            $branch = BranchOffice::create([
                "name" => request('name'),
                "description" => request('description'),
                "nit" => request('nit'),
                "email" => request('email'),
                "city" => request('city'),
                "longitude" => request('longitude'),
                "latitude" => request('latitude'),
                "address" => request('address'),
                "phone" => request('phone'),
                "close" => request('close'),
                "hours_24" => request('hours_24'),
                "state_id" => request('state_id'),
                "db_name" => $db_name,
                "company_id" => request('company_id'),
            ]);

            $principal_user = User::create([
                'name' => request('name'),
                'last_name' => request('name'),
                'phone' => request('phone'),
                'address' => request('address'),
                'dni' => request('nit'),
                'email' => request('email'),
                'password' => bcrypt('123456'),
                'phanton_user' => 0,
                'state_id' => 1
            ]);

            #Create the phatons users

            $phanton = User::insert([
                [
                    'name' => request('name'),
                    'last_name' => request('name'),
                    'phone' => request('phone'),
                    'address' => request('address'),
                    'dni' => request('nit'),
                    'email' => request('email'),
                    'password' => bcrypt('123456'),
                    'phanton_user' => 0,
                    'state_id' => 1
                ]
            ]);

            $branch_user = BranchUser::create([
                'user_id' => $principal_user->id,
                'branch_id' => $branch->id
            ]);

            # User info
            $data = array();

            # Create db
            $new_db = NewDatabaseHelper::newBarberShopDatabase($db_name, $data);

            #Set new connection
            $set_new_connection = SetConnectionHelper::setByDBName($branch->db_name);

            if($new_db != 1){
                return response()->json(['response' => ['error' => [$new_db]]], 400);
            }

            DB::connection($branch->db_name)->beginTransaction();
            # Add user to company
            $user = CUser::on($branch->db_name)->create([
                'name' => request('name'),
                'last_name' => request('name'),
                'phone' => request('phone'),
                'address' => request('address'),
                'dni' => request('nit'),
                'email' => request('email'),
                'phanton_user' => 0,
                'principal_id' => $principal_user->id,
                'state_id' => 1
            ]);

            $user_role = UserRole::on($branch->db_name)->create([
                'user_id' => $user->id,
                'role_id' => 1
            ]);

        }catch(Exception $e){
            DB::rollback();
            if($new_db == 1){
                DB::connection($branch->db_name)->rollback();
            }
        }

        DB::connection($branch->db_name)->commit();
        DB::commit();
        return response()->json(['response' => 'Sucursal creada con exito'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $branch = BranchOffice::select('branch_office.id', 'branch_office.name', 'branch_office.description', 'branch_office.nit', 'branch_office.email', 'branch_office.city', 'branch_office.longitude', 'branch_office.latitude', 'branch_office.address', 'branch_office.phone', 'branch_office.close', 'branch_office.hours_24', 'branch_office.state_id', 'branch_office.company_id', 'c.name as company_name', 'c.description as company_description', 'c.nit as company_nit')
        ->join('company as c', 'branch_office.company_id', 'c.id')
        ->where('branch_office.id', '!=', 1)
        ->find($id);

        return response()->json(['response' => $branch], 200);
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
            'name' => 'required|min:1|max:125',
            'description' => 'required',
            'nit' => 'required|min:1|max:30',
            'email' => 'required|email',
            'city' => 'required',
            'longitude' => 'required',
            'latitude' => 'required',
            'address' => 'required',
            'phone' => 'required|min:7|max:10',
            'close' => 'required|integer',
            'hours_24' => 'required|integer',
            'state_id' => 'required|numeric|exists:branch_state,id',
            'company_id' => 'required|numeric|exists:company,id'
        ]);
        if($validator->fails())
        {
          return response()->json(['response' => ['error' => $validator->errors()->all()]],400);
        }

        $branch = BranchOffice::where('id', '!=', 1)
        ->find($id);

        if(!$branch){
            return response()->json(['response' => ['error' => ['Sucursal no encontrada']]], 404);
        }

        $branch->name = request('name');
        $branch->description = request('description');
        $branch->nit = request('nit');
        $branch->email = request('email');
        $branch->city = request('city');
        $branch->longitude = request('longitude');
        $branch->latitude = request('latitude');
        $branch->address = request('address');
        $branch->phone = request('phone');
        $branch->close = request('close');
        $branch->hours_24 = request('hours_24');
        $branch->state_id = request('state_id');
        $branch->company_id = request('company_id');
        $branch->update();

        return response()->json(['response' => 'Sucursal actualizada con exito'], 200);
    }

}
