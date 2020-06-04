<?php

namespace App\Http\Controllers\Api\Administration\Grooming;

use App\Http\Controllers\Controller;
use App\Models\CUser;
use App\Models\Grooming\ClientService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClientServiceController extends Controller
{
    public function __construct()
    {
        # List turn permission
        $this->middleware('permission:/list_services')->only(['clientServiceList', 'clientServiceDetails']);
        $this->middleware('permission:/update_services')->only(['modifyService']);
        # Get connection
        $this->middleware('set_connection');

    }
    public function clientServiceList(Request $request)
    {
        $validator=\Validator::make($request->all(),[
            'state_id' => 'bail|integer',
            'employee_id' => 'bail|integer',
            'dni' => 'bail|max:15',
            'service_id' => 'bail|integer',
            'date_start' => 'bail|date_format:"Y-m-d"|date',
            'date_end' => 'bail|date_format:"Y-m-d"|date',
        ]);
        if($validator->fails())
        {
          return response()->json(['response' => ['error' => $validator->errors()->all()]],400);
        }

        $services = ClientService::on('connectionDB')->select('client_service.id', 'client_service.employee_id', 'client_service.user_id', 'client_service.user_service_id', 'client_service.dni', 'client_service.start_at', 'client_service.acepted_by', 'client_service.service_id', 'client_service.paid_out', 'client_service.hours', 'client_service.date_start', 'client_service.date_end', 'client_service.state_id', 'sl.name as service_name', 'sl.description as service_description', 'sl.price_per_hour', 'sl.hours_max', 'client_service.tracking')
        ->join('service_list as sl', 'client_service.service_id', 'sl.id')
        ->state(request('state_id'))
        ->employee(request('employee_id'))
        ->dni(request('dni'))
        ->service(request('service_id'))
        ->get();

        foreach ($services as $service) {
            if($service->employee_id != null){
                $employee = CUser::on('connectionDB')->where('id', $service->employee_id)->first();
            }else{
                $employee = [];
            }

            $client = User::where('id', $service->user_id)->first();

            if($service->acepted_by != null){
                $acepted = CUser::on('connectionDB')->where('id', $service->acepted_by)->first();
            }else{
                $acepted = [];
            }

            $service->employee = $employee;
            $service->acepted = $acepted;
            $service->client = $client;

        }

        return response()->json(['response' => $services], 200);
    }

    public function clientServiceDetails($id)
    {
        $service = ClientService::on('connectionDB')->select('client_service.id', 'client_service.employee_id', 'client_service.user_id', 'client_service.user_service_id', 'client_service.dni', 'client_service.start_at', 'client_service.acepted_by', 'client_service.service_id', 'client_service.paid_out', 'client_service.hours', 'client_service.date_start', 'client_service.date_end', 'client_service.state_id', 'sl.name as service_name', 'sl.description as service_description', 'sl.price_per_hour', 'sl.hours_max', 'client_service.tracking')
        ->join('service_list as sl', 'client_service.service_id', 'sl.id')
        ->state(request('state_id'))
        ->employee(request('employee_id'))
        ->dni(request('dni'))
        ->service(request('service_id'))
        ->where('client_service.id', $id)
        ->first();

        if($service){
            if($service->employee_id != null){
                $employee = CUser::on('connectionDB')->where('id', $service->employee_id)->first();
            }else{
                $employee = [];
            }

            $client = User::where('id', $service->user_id)->first();

            if($service->acepted_by != null){
                $acepted = CUser::on('connectionDB')->where('id', $service->acepted_by)->first();
            }else{
                $acepted = [];
            }

            $service->employee = $employee;
            $service->acepted = $acepted;
            $service->client = $client;
        }


        return response()->json(['response' => $service], 200);
    }

    public function modifyServiceClient(Request $request, $id)
    {
        $validator=\Validator::make($request->all(),[
            'state_id' => 'bail|integer|required',
        ]);
        if($validator->fails())
        {
          return response()->json(['response' => ['error' => $validator->errors()->all()]],400);
        }

        $state = DB::connection('connectionDB')->table('service_state')->where('id', request('state_id'))->first();

        if(!$state){
            return response()->json(['response' => ['error' => ['Estado no encontrado.']]], 400);
        }

        $user = CUser::on('connectionDB')->where('principal_id', Auth::id())->first();

        $service = ClientService::on('connectionDB')
        ->where('id', $id)
        ->first();

        if(!$service){
            return response()->json(['response' => ['error' => ['Servicio no encontrado.']]], 400);
        }

        $new_state = DB::connection('connectionDB')->table('service_state')->where('id', $service->state_id)->first();


        if($service->tracking == null){
            $tracking = array();
        }else{
            $tracking = json_decode($service->tracking);

        }

        array_push($tracking, array(
            'user_id' => $user->id,
            'user_name' => $user->name.' '.$user->last_name,
            'last_state' => $new_state->name,
            'new_state' => $state->name,
            'updated_at' => date('Y-m-d H:i:s')
        ));

        $service->state_id = request('state_id');
        $service->tracking = json_encode($tracking);
        $service->update();

        return response()->json(['response' => 'Success'], 400);
    }

    public function assignEmployee(Request $request, $id)
    {
        $validator=\Validator::make($request->all(),[
            'employee_id' => 'bail|integer|required',
            'state_id' => 'bail|integer',
        ]);
        if($validator->fails())
        {
          return response()->json(['response' => ['error' => $validator->errors()->all()]],400);
        }

        $user = CUser::on('connectionDB')->where('principal_id', Auth::id())->first();

        $employee = CUser::on('connectionDB')->select('users.id', 'users.name', 'users.last_name', 'users.state_id')
        ->join('user_has_role as ur', 'users.id', 'ur.user_id')
        ->where('ur.role_id', 2)
        ->where('users.id', request('employee_id'))->first();

        if(!$employee){
            return response()->json(['response' => ['error' => ['Empleado no encontrado']]], 400);
        }

        $employee_availability = ClientService::on('connectionDB')->select('client_service.id')
        ->join('users as u', 'client_service.employee_id', 'u.id')
        ->whereNotIn('client_service.state_id', [2, 5])
        ->where('client_service.employee_id', $employee->id)
        ->first();

        if($employee_availability){
            return response()->json(['response' => ['error' => ['El empleado no se encuentra disponible, para poder asignarlo necesitas cambiar de estado el servicio que tiene en proceso en este momento']]], 400);
        }

        $service = ClientService::on('connectionDB')
        ->where('id', $id)
        ->first();

        if(!$service){
            return response()->json(['response' => ['error' => ['Servicio no encontrado.']]], 400);
        }


        if($service->tracking == null){
            $tracking = array();
        }else{
            $tracking = json_decode($service->tracking);
        }

        if(empty(request('state_id'))){
            array_push($tracking, array(
                'user_id' => $user->id,
                'user_name' => $user->name.' '.$user->last_name,
                'employee_asigned_id' => $employee->id,
                'employee_asigned_name' => $employee->name.' '.$employee->last_name,
                'updated_at' => date('Y-m-d H:i:s')
            ));
        }else{
            $state = DB::connection('connectionDB')->table('service_state')->where('id', request('state_id'))->first();
            $new_state = DB::connection('connectionDB')->table('service_state')->where('id', $service->state_id)->first();
            array_push($tracking, array(
                'user_id' => $user->id,
                'user_name' => $user->name.' '.$user->last_name,
                'last_state' => $new_state->name,
                'new_state' => $state->name,
                'employee_asigned_id' => $employee->id,
                'employee_asigned_name' => $employee->name.' '.$employee->last_name,
                'updated_at' => date('Y-m-d H:i:s')
            ));

            $service->state_id = request('state_id');
        }

        $service->employee_id = $employee->id;
        $service->tracking = json_encode($tracking);
        $service->update();

        return response()->json(['response' => 'Success'], 400);


    }

}
