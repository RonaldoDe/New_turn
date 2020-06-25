<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\HelpersData;
use App\Http\Controllers\Helper\SetConnectionHelper;
use App\Models\CUser;
use App\Models\Grooming\ClientService;
use App\Models\Master\BranchOffice;
use App\Models\Master\MasterCompany;
use App\Models\PaymentData;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComplementsListController extends Controller
{
    public function servicesList(Request $request)
    {
        $validator=\Validator::make($request->all(),[
            'branch_id' => 'bail|required|exists:branch_office,id'
        ]);
        if($validator->fails())
        {
          return response()->json(['response' => ['error' => $validator->errors()->all()]],400);
        }
        # --------------------- Set connection ------------------------------------#
        $branch = BranchOffice::where('id', '!=', 1)->find(request('branch_id'));

        if(!$branch){
            return response()->json(['response' => ['error' => ['Sucursal no encontrada']]], 400);
        }

        $set_connection = SetConnectionHelper::setByDBName($branch->db_name);
        # --------------------- Set connection ------------------------------------#

        $services = Service::on($branch->db_name)->get();

        return response()->json(['response' => $services], 200);
    }

    public function employeesList(Request $request)
    {
        $validator=\Validator::make($request->all(),[
            'branch_id' => 'bail|required|exists:branch_office,id',
            'service_id' => 'bail|required|integer',
            'date_start' => 'bail|required|date_format:"Y-m-d H:i:s"|date',
            'name' => 'bail'
        ]);
        if($validator->fails())
        {
          return response()->json(['response' => ['error' => $validator->errors()->all()]],400);
        }
        # --------------------- Set connection ------------------------------------#
        $branch = BranchOffice::where('id', '!=', 1)->find(request('branch_id'));

        if(!$branch){
            return response()->json(['response' => ['error' => ['Sucursal no encontrada']]], 400);
        }

        $compnay = MasterCompany::find($branch->company_id);

        $set_connection = SetConnectionHelper::setByDBName($branch->db_name);
        # --------------------- Set connection ------------------------------------#

        if($compnay->type_id == 1){

            $service = Service::on($branch->db_name)->find(request('service_id'));

            if(!$service){
                return response()->json(['response' => ['error' => 'Servicio no encontrado.']], 400);
            }

            $date_end = date('Y-m-d H:i:s', strtotime('+'.$service->time.' minute', strtotime(date(date('Y-m-d H:i:s')))));

            $validate_business_days = HelpersData::employeeBusinessDays(date('Y-m-d H:i:s'), $date_end, $service->id, $branch->db_name);

            if(count($validate_business_days) < 1){
                return response()->json(['response' => ['error' => ['No hay empleados disponibles para la hora solicitada']]], 400);
            }

            $employees = CUser::on($branch->db_name)->select('users.id', 'users.name', 'users.last_name')
            ->join('user_has_role as ur', 'users.id', 'ur.user_id')
            ->where('ur.role_id', 2)
            ->where('users.phanton_user', 0)
            ->name(request('name'))
            ->where('ets.service_id', $service->id)
            ->where('ur.role_id', 2)
            ->whereIn('users.id', $validate_business_days)
            ->get();
        }else{

            $service = Service::on($branch->db_name)->find(request('service_id'));

            if(!$service){
                return response()->json(['response' => ['error' => 'Servicio no encontrado.']], 400);
            }

            $current_date_more_waiting = date('Y-m-d H:i:s', strtotime('+'.$service->wait_time.' minute', strtotime(date('Y-m-d H:i:s'))));

            if(request('date_start') < $current_date_more_waiting){
                return response()->json(['response' => ['error' => ['La fecha de inicio debe ser mayor que la fecha actual MAS '.$service->wait_time.' minutos de la llegada de el empleado..']]], 400);
            }


            $date_end = date('Y-m-d H:i:s', strtotime('+'.$service->time.' minute', strtotime(date(request('date_start')))));

            $validate_business_days = HelpersData::employeeBusinessDays(request('date_start'), $date_end, $service->id, $branch->db_name);

            if(count($validate_business_days) < 1){
                return response()->json(['response' => ['error' => ['No hay empleados disponibles para la hora solicitada']]], 400);
            }
            $employees = CUser::on($branch->db_name)->select('users.id','users.name', 'users.last_name')
            ->join('user_has_role as ur', 'users.id', 'ur.user_id')
            ->join('employee_type_employee as ete', 'users.id', 'ete.employee_id')
            ->join('employee_type_service as ets', 'ete.employee_type_id', 'ets.employee_type_id')
            ->where('ets.service_id', $service->id)
            ->where('ur.role_id', 2)
            ->whereIn('users.id', $validate_business_days)
            ->get();

            $collect = collect($employees)->pluck('id');

            $client_service = ClientService::on($branch->db_name)
            ->whereIn('employee_id', $collect)
            ->whereIn('state_id', [2, 5])
            ->get();

            $pass = 0;
            $employees_valid = array();
            foreach ($client_service as $client) {
                # Validar los rangos de fechas
                if(request('date_start') >= $client->date_start && request('date_start') <= $client->date_end)
                {
                    $pass++;
                }

                if($date_end >= $client->date_start && $date_end <= $client->date_end)
                {
                    $pass++;
                }

                if($client->date_start >= request('date_start') && $client->date_start <= $date_end)
                {
                    $pass++;
                }

                if($client->date_end >= request('date_start') && $client->date_end <= $date_end)
                {
                    $pass++;
                }

                if($pass == 0){
                    array_push($employees_valid, $client->employee_id);
                }
            }


        }

        $employees_list = CUser::on($branch->db_name)->select('users.id','users.name', 'users.last_name')
        ->whereIn('id', $employees_valid)
        ->get();

        return response()->json(['response' => $employees_list], 200);
    }

    public function paymentData(Request $request)
    {
        $validator=\Validator::make($request->all(),[
            'payment_method' => 'bail|required|exists:payment_method,id',
            'data' => 'bail|required'
        ]);
        if($validator->fails())
        {
          return response()->json(['response' => ['error' => $validator->errors()->all()]],400);
        }

        $payment = PaymentData::create([
            'user_id' => Auth::id(),
            'payment_method' => request('payment_method'),
            'data' => json_encode(request('data')),
            'state' => 1,
        ]);

        return response()->json(['response' => $payment], 200);
    }

    public function listPaymentData(Request $request)
    {

        $payment = PaymentData::where('user_id', Auth::id())->get();

        return response()->json(['response' => $payment], 200);
    }
}
