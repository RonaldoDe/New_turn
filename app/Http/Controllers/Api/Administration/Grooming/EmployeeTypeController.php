<?php

namespace App\Http\Controllers\Api\Administration\Grooming;

use App\Http\Controllers\Controller;
use App\Models\Grooming\EmployeeType;
use App\Models\Grooming\EmployeeTypeService;
use App\Models\Master\BranchOffice;
use App\Models\Master\BranchUser;
use App\Models\Master\MasterCompany;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EmployeeTypeController extends Controller
{

    public function __construct()
    {
        # List services config permission
        $this->middleware('permission:/list_employee_type')->only(['index', 'show']);
        # Modify services config permission
        $this->middleware('permission:/create_employee_type')->only(['store', 'update', 'destroy']);
        # Get connection
        $this->middleware('set_connection');

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::find(Auth::id());

        $branch_user = BranchUser::where('user_id', $user)->first();

        $branch = BranchOffice::find($branch_user->branch_id);

        if(!$branch){
            return response()->json(['response' => ['error' => ['Sucursal no encontrada.']]], 400);
        }

        $company = MasterCompany::where($branch->company_id)->first();

        $employees_types = EmployeeType::on('connectionDB')->get();

        foreach ($employees_types as $employee_type) {
            if($company->type_id == 2){
                $employee_type_service = EmployeeTypeService::on('connectionDB')->select('sl.id', 'sl.name', 'sl.description', 'sl.price', 'sl.opening_hours', 'sl.state', 'sl.time')
                ->join('service_list as sl', 'employee_type_service.service_id', 'sl.id')
                ->where('employee_type_service.employee_type_id', $employee_type->id)
                ->get();
            }else{
                $employee_type_service = EmployeeTypeService::on('connectionDB')->select('sl.id', 'sl.name', 'sl.description', 'sl.price_per_hour', 'sl.hours_max', 'sl.wait_time', 'sl.opening_hours', 'sl.state')
                ->join('service_list as sl', 'employee_type_service.service_id', 'sl.id')
                ->where('employee_type_service.employee_type_id', $employee_type->id)
                ->get();
            }

            $employee_type->services = $employee_type_service;
        }

        return response()->json(['response' => $employees_types], 200);
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
            'name' => 'required|max:75',
            'description' => 'required',
            'state' => 'bail|required|integer',
            'add_array' => 'bail|array',
        ]);
        if($validator->fails())
        {
          return response()->json(['response' => ['error' => $validator->errors()->all()]],400);
        }

        DB::connection('connectionDB')->beginTransaction();
        try{
            $employee_type = EmployeeType::on('connectionDB')->create([
                'name' => request('name'),
                'description' => request('description'),
                'state' => request('state')
            ]);

            foreach (request('add_array') as $add_array) {
                # We need to add the role´s id for each record in the list.
                $validate_employee_type_service = EmployeeTypeService::on('connectionDB')->where('employee_type_id', $employee_type->id)->where('service_id', $add_array)->first();

                if(!$validate_employee_type_service){
                    $employee_type_service = EmployeeTypeService::on('connectionDB')->create([
                        'employee_type_id' => $employee_type->id,
                        'service_id' => $add_array,
                    ]);
                }
            }
        }catch(Exception $e){
            DB::connection('connectionDB')->rollback();
            return response()->json(['response' => ['error' => ['Error al crear el tipo de empleado'. $e->getMessage()]]], 400);
        }

        DB::connection('connectionDB')->commit();
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
        $user = User::find(Auth::id());

        $branch_user = BranchUser::where('user_id', $user)->first();

        $branch = BranchOffice::find($branch_user->branch_id);

        if(!$branch){
            return response()->json(['response' => ['error' => ['Sucursal no encontrada.']]], 400);
        }

        $company = MasterCompany::where($branch->company_id)->first();

        $employee_type = EmployeeType::on('connectionDB')->find($id);

        if(!$employee_type){
            return response()->json(['response' => ['error' => ['Tipo de empleado no encontrado']]], 400);
        }

        if($company->type_id == 2){
            $employee_type_service = EmployeeTypeService::on('connectionDB')->select('sl.id', 'sl.name', 'sl.description', 'sl.price', 'sl.opening_hours', 'sl.state', 'sl.time')
            ->join('service_list as sl', 'employee_type_service.service_id', 'sl.id')
            ->where('employee_type_service.employee_type_id', $employee_type->id)
            ->get();
        }else{
            $employee_type_service = EmployeeTypeService::on('connectionDB')->select('sl.id', 'sl.name', 'sl.description', 'sl.price_per_hour', 'sl.hours_max', 'sl.wait_time', 'sl.opening_hours', 'sl.state')
            ->join('service_list as sl', 'employee_type_service.service_id', 'sl.id')
            ->where('employee_type_service.employee_type_id', $employee_type->id)
            ->get();
        }

        $employee_type->services = $employee_type_service;

        return response()->json(['response' => $employee_type], 200);
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
            'name' => 'required|max:75',
            'description' => 'required',
            'state' => 'bail|required|integer',
            'add_array' => 'bail|array',
            'delete_array' => 'bail|array',
        ]);
        if($validator->fails())
        {
          return response()->json(['response' => ['error' => $validator->errors()->all()]],400);
        }

        DB::connection('connectionDB')->beginTransaction();
        try{

            $employee_type = EmployeeType::on('connectionDB')->find($id);

            if(!$employee_type){
                return response()->json(['response' => ['error' => ['Tipo de empleado no encontrado.']]], 400);
            }

            $employee_type->name = request('name');
            $employee_type->description = request('description');
            $employee_type->state = request('state');
            $employee_type->update();


            foreach (request('delete_array') as $delete_array) {
                # We need to remove the role´s id for each record in the list.
                $validate_employee_type_service = EmployeeTypeService::on('connectionDB')->where('employee_type_id', $employee_type->id)->where('service_id', $delete_array)->first();

                if($validate_employee_type_service){
                    $validate_employee_type_service->delete();
                }
            }

            foreach (request('add_array') as $add_array) {
                # We need to add the role´s id for each record in the list.
                $validate_employee_type_service = EmployeeTypeService::on('connectionDB')->where('employee_type_id', $employee_type->id)->where('service_id', $add_array)->first();

                if(!$validate_employee_type_service){
                    $employee_type_service = EmployeeTypeService::on('connectionDB')->create([
                        'employee_type_id' => $employee_type->id,
                        'service_id' => $add_array,
                    ]);
                }
            }
        }catch(Exception $e){
            DB::connection('connectionDB')->rollback();
            return response()->json(['response' => ['error' => ['Error al actualizar el tipo de empleado'. $e->getMessage()]]], 400);
        }

        DB::connection('connectionDB')->commit();
        return response()->json(['response' => 'Success'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
