<?php

namespace App\Http\Controllers\Api\Administration\Grooming;

use App\Http\Controllers\Controller;
use App\Models\CUser;
use App\Models\Grooming\ClientService;
use App\User;
use Illuminate\Http\Request;

class ClientServiceController extends Controller
{
    public function __construct()
    {
        # List turn permission
        $this->middleware('permission:/list_services')->only(['clientServiceList']);
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

        $services = ClientService::on('connectionDB')->select('client_service.id', 'client_service.employee_id', 'client_service.user_id', 'client_service.user_service_id', 'client_service.dni', 'client_service.start_at', 'client_service.acepted_by', 'client_service.service_id', 'client_service.paid_out', 'client_service.hours', 'client_service.date_start', 'client_service.date_end', 'client_service.state_id', 'sl.name as service_name', 'sl.description as service_description', 'sl.price_per_hour', 'sl.hours_max')
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
        $service = ClientService::on('connectionDB')->select('client_service.id', 'client_service.employee_id', 'client_service.user_id', 'client_service.user_service_id', 'client_service.dni', 'client_service.start_at', 'client_service.acepted_by', 'client_service.service_id', 'client_service.paid_out', 'client_service.hours', 'client_service.date_start', 'client_service.date_end', 'client_service.state_id', 'sl.name as service_name', 'sl.description as service_description', 'sl.price_per_hour', 'sl.hours_max')
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
}