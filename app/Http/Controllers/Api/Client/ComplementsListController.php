<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\HelpersData;
use App\Http\Controllers\Helper\SetConnectionHelper;
use App\Models\CUser;
use App\Models\Grooming\ClientService;
use App\Models\Grooming\Service as AppService;
use App\Models\Master\BranchOffice;
use App\Models\Master\MasterCompany;
use App\Models\PaymentData;
use App\Models\Service;
use DateTime;
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
            'date_start' => 'bail|date_format:"Y-m-d H:i:s"|date',
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

        if($compnay->type_id == 2){

            $service = Service::on($branch->db_name)->find(request('service_id'));

            if(!$service){
                return response()->json(['response' => ['error' => 'Servicio no encontrado.']], 400);
            }

            $date_end = date('Y-m-d H:i:s', strtotime('+'.$service->time.' minute', strtotime(date(date('Y-m-d H:i:s')))));

            /*$validate_business_days = HelpersData::employeeBusinessDays(date('Y-m-d H:i:s'), $date_end, $service->id, $branch->db_name);

            if(count($validate_business_days) < 1){
                return response()->json(['response' => ['error' => ['No hay empleados disponibles para la hora solicitada']]], 400);
            }*/

            $employees = CUser::on($branch->db_name)->select('users.id', 'users.name', 'users.last_name')
            ->join('user_has_role as ur', 'users.id', 'ur.user_id')
            ->join('employee_type_employee as ete', 'users.id', 'ete.employee_id')
            ->join('employee_type_service as ets', 'ete.employee_type_id', 'ets.employee_type_id')
            ->where('ur.role_id', 2)
            ->where('users.phanton_user', 0)
            ->name(request('name'))
            ->where('ets.service_id', $service->id)
            ->where('ur.role_id', 2)
            #->whereIn('users.id', $validate_business_days)
            ->get();

            return response()->json(['response' => $employees], 200);

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
            if($validate_business_days == 0){
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
            $employees_valid = $collect;

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

                if($pass > 0){
                    $data_to_delete = collect($employees_valid)->search($client->employee_id);
                    unset($employees_valid[$data_to_delete]);
                }
            }

        }

        $employees_list = CUser::on($branch->db_name)->select('users.id','users.name', 'users.last_name')
        ->whereIn('id', collect($employees_valid)->values())
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

    public function businessHours(Request $request)
    {
        $validator=\Validator::make($request->all(),[
            'branch_id' => 'bail|required|exists:branch_office,id',
            'service_id' => 'bail|numeric',
            'date' => 'bail|date_format:"Y-m-d"|date',
        ]);
        if($validator->fails())
        {
          return response()->json(['response' => ['error' => $validator->errors()->all()]],400);
        }
        # --------------------- Set connection ------------------------------------#

        $branch = BranchOffice::find(request('branch_id'));

        $company = MasterCompany::find($branch->company_id);

        $set_connection = SetConnectionHelper::setByDBName($branch->db_name);
        # --------------------- Set connection ------------------------------------#

        if($company->type_id != 3){
            return response()->json(['response' => ['error' => ['Error']]], 400);
        }

        if(!empty(request('service_id'))){


            // Validar los empleados disponibles por el servicio, luego ver cuales pueden hacer trabajo en cada una de los servicios ocupados
            // Agregar en un array cuales es tán tomados y hacer un pluck para hacer la consulta de usuarios y agregarlo a el detalle de cada servicio como la cantidad de disponibles y la lista

            $employees = CUser::on($branch->db_name)->select('users.id','users.name', 'users.last_name')
            ->join('user_has_role as ur', 'users.id', 'ur.user_id')
            ->join('employee_type_employee as ete', 'users.id', 'ete.employee_id')
            ->join('employee_type_service as ets', 'ete.employee_type_id', 'ets.employee_type_id')
            ->where('ets.service_id', request('service_id'))
            ->where('ur.role_id', 2)
            ->get();

            $client_services = ClientService::on($branch->db_name)->select('client_service.id', 'client_service.employee_id', 'client_service.service_id', 'client_service.date_start', 'client_service.date_end',
            'client_service.state_id', 'sl.name as service_name', 'sl.description as service_description', 'sl.price_per_hour', 'u.name as employee_name', 'u.last_name as employee_last_name')
            ->join('service_list as sl', 'client_service.service_id', 'sl.id')
            ->join('users as u', 'client_service.employee_id', 'u.id')
            ->whereIn('client_service.state_id', [2, 5])
            ->whereIn('client_service.employee_id', collect($employees)->pluck('id'))
            ->where('client_service.date_start', '>=', request('date').' 00:00:00')
            ->where('client_service.date_end', '<=', request('date').' 23:59:59')
            ->get();

            $data_array = array();

            foreach ($client_services as $client_master) {
                $old_date_start = $client_master->date_start;
                $old_date_end = $client_master->date_end;

                $date_start = new DateTime($client_master->date_start);
                $date_end = new DateTime($client_master->date_end);
                $total_time = $date_start->diff($date_end)->i;
                $testy = array();
                for ($i=0; $i < $total_time; $i += $branch->minimun_time) {

                    $new_date_start = $date_start->modify('+'.$i.' minute')->format('Y-m-d H:i:s');
                    $new_date_end = date('Y-m-d H:i:s', strtotime('+'.$branch->minimun_time.' minute', strtotime($new_date_start)));

                    array_push($testy, $i);
                    # Sumar los "10" min desde la hora inicial y hacer el resto de el código y validar esos tiempos por cada usuario
                    # Crear un registro por cada vuelta de el for, como si fuese un registro de db pero con las horas divididas por el minimo

                    $test = collect($employees)->pluck('id');

                    $client_service = ClientService::on($branch->db_name)
                    ->where('id', '!=', $client_master->id)
                    ->where('employee_id', '!=', $client_master->employee_id)
                    ->whereIn('employee_id', $test)
                    ->whereIn('state_id', [2, 5])
                    ->get();

                    $pass = 0;
                    $employees_valid = $test;

                    foreach ($client_service as $client) {
                        # Validar los rangos de fechas
                        if($new_date_start >= $client->date_start && $new_date_start <= $client->date_end)
                        {
                            $pass++;
                        }

                        if($new_date_end >= $client->date_start && $new_date_end <= $client->date_end)
                        {
                            $pass++;
                        }

                        if($client->date_start >= $new_date_start && $client->date_start <= $new_date_end)
                        {
                            $pass++;
                        }

                        if($client->date_end >= $new_date_start && $client->date_end <= $new_date_end)
                        {
                            $pass++;
                        }

                        if($pass > 0){
                            $data_to_delete = collect($employees_valid)->search($client->employee_id);
                            unset($employees_valid[$data_to_delete]);
                        }

                    }
                    $data_to_delete = collect($employees_valid)->search($client_master->employee_id);
                    unset($employees_valid[$data_to_delete]);

                    $employees_list = CUser::on($branch->db_name)->select('users.id','users.name', 'users.last_name')
                    ->whereIn('id', collect($employees_valid)->values())
                    ->get();

                    # $client_master->available_employees = $employees_list;
                    # $client_master->date_start = $new_date_start;
                    # $client_master->date_end = $new_date_end;
                    array_push($data_array, array(
                        "id"=> 9,
                        "employee_id"=> 3,
                        "service_id"=> 1,
                        "date_start"=> $new_date_start,
                        "date_end"=> $new_date_end,
                        "state_id"=> 5,
                        "service_name"=> "General",
                        "service_description"=> "Aseo general.",
                        "price_per_hour"=> "20000",
                        "employee_name"=> "Ronal2",
                        "employee_last_name"=> "Camacho",
                        "available_employees"=> $employees_list
                    ));
                }


            }
            return response()->json(['response' => $data_array], 200);


        }else{
            $client_services = ClientService::on($branch->db_name)->select('client_service.id', 'client_service.employee_id', 'client_service.service_id', 'client_service.date_start', 'client_service.date_end',
            'client_service.state_id', 'sl.name as service_name', 'sl.description as service_description', 'sl.price_per_hour', 'u.name as employee_name', 'u.last_name as employee_last_name')
            ->join('service_list as sl', 'client_service.service_id', 'sl.id')
            ->join('users as u', 'client_service.employee_id', 'u.id')
            ->whereIn('client_service.state_id', [2, 5])
            ->get();
        }

        return response()->json(['response' => $client_services], 200);

    }
}
